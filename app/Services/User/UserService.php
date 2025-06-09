<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\UserProfileDTO;
use App\Models\NotificationCode;
use App\Models\User;
use App\Services\Notifications\ResetPasswordCode;
use App\Services\Notifications\VerifyRegistrationCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use LogicException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class UserService
{
    public const string ACTION_VERIFY_REG = 'verify-registration';
    public const string ACTION_RESET_PASS = 'reset-password';

    public function authorize(string $email, string $password): ?string
    {
        try {
            $user = User::where('email', $email)->firstOrFail();
        } catch (\Exception $e) {
            throw new AuthenticationException('Неверные учетные данные');
        }

        $token = null;
        if (Hash::check($password, $user->password)) {
            $token = $this->createToken($user);
        }

        return $token;
    }

    public function create(UserProfileDTO $dto): string
    {
        $user = new User();

        $user->fill([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => $dto->password,
        ])->save();

        Auth::login($user, true);

        $this->sendVerifyNotification($user);

        return $this->createToken($user);
    }

    public function registerWithYandex(object $user): string
    {
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'first_name' => $user->user['display_name'],
            'password'   => Hash::make(Str::random(24)),
        ]);

        Auth::login($user, true);

        return $this->createToken($user);
    }

    private function createToken(User $user): string
    {
        return $user->createToken(name: 'auth-token', expiresAt: now()->addDays(7))->plainTextToken;
    }

    public function update(UserProfileDTO $dto, User $user): User
    {
        if ($dto->email !== $user->email) {
            $user->email_verified_at = null;

            $this->sendVerifyNotification($user);
        }

        $user->fill($dto->jsonSerialize())->save();

        return $user;
    }

    public function verifyEmailAddress(int $code, User $user): void
    {
        $notification = NotificationCode::where([
            'user_id' => $user->id(),
            'code'    => $code,
            'action'  => self::ACTION_VERIFY_REG,
        ])->first();

        if (!$notification) {
            throw new NotFoundHttpException('Информация не найдена, проверьте правильность ввода данных или запросите новый код подтверждения.');
        }

        $user->markEmailAsVerified();

        $notification->delete();
    }

    public function changePassword(User $user, string $password): void
    {
        $user->forceFill([
            'password' => Hash::make($password),
        ])->save();
    }

    public function sendVerifyNotification(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw new LogicException('Текущий аккаунт уже подтвержден');
        }

        $code = (string)rand(100000, 999999);

        NotificationCode::updateOrCreate([
            'user_id' => $user->id,
            'action'  => self::ACTION_VERIFY_REG,
        ], [
            'code' => $code,
        ]);

        $user->notify(new VerifyRegistrationCode($code));
    }

    public function sendResetPasswordCode(string $email): void
    {
        try {
            $user = User::where('email', $email)->firstOrFail();
        } catch (\Exception $e) {
            throw new NotFoundHttpException('Пользователь с таким email не найден');
        }

        $this->sendResetPasswordNotification($user);
    }

    public function setPasswordByCode(string $email, string $code, string $password): void
    {
        try {
            $user = User::where('email', $email)->firstOrFail();
        } catch (\Exception $e) {
            throw new NotFoundHttpException('Пользователь с таким email не найден');
        }

        $codeRecord = NotificationCode::where(['code' => $code, 'user_id' => $user->id()])->first();

        if (!$codeRecord) {
            throw new NotFoundHttpException('Код подтверждения не найден или уже использован');
        }

        $user->password = Hash::make($password);
        $user->save();

        $codeRecord->delete();
    }

    private function sendResetPasswordNotification(User $user): void
    {
        $code = (string)rand(100000, 999999);

        NotificationCode::updateOrCreate(
            [
                'user_id' => $user->id,
                'action'  => self::ACTION_RESET_PASS,
            ],
            [
                'code' => $code,
            ]
        );

        $user->notify(new ResetPasswordCode($code));
    }
}
