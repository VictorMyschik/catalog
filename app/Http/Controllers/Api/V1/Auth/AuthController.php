<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTO\UserProfileDTO;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Auth\Request\AuthenticateRequest;
use App\Http\Controllers\Api\V1\Auth\Request\ChangePasswordRequest;
use App\Http\Controllers\Api\V1\Auth\Request\RegisterRequest;
use App\Http\Controllers\Api\V1\Auth\Request\ResetPasswordRequest;
use App\Http\Controllers\Api\V1\Auth\Request\UpdatePasswordRequest;
use App\Http\Controllers\Api\V1\Auth\Request\VerifyRegistrationRequest;
use App\Http\Controllers\Api\V1\Response\ResponseFactory;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends APIController
{
    public function __construct(
        private readonly UserService       $userService,
        protected readonly ResponseFactory $responseFactory,
    ) {}

    #[OA\Post(
        path: "/api/v1/register",
        description: "Регистрация нового пользователя. Успешный ответ будет содержать Bearer токен, который нужно использовать для авторизации в других запросах.",
        summary: "Регистрация",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/RegisterRequest")
        ),
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful registration",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "ok"),
                        new OA\Property(
                            property: "content",
                            properties: [
                                new OA\Property(property: "token", type: "string", example: "54|zSA0wCsYADhnwylBCo86NPCKnz1jEYGns7cJbY8543e3b2ad")
                            ],
                            type: "object"
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError")),
            new OA\Response(response: 422, description: "Validation error", content: new OA\JsonContent(ref: "#/components/schemas/ValidationError"))
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = new UserProfileDTO(
            email: $request->getEmail(),
            name: $request->getName(),
            password: Hash::make($request->getPassword()),
        );

        return $this->apiResponse(['token' => $this->userService->create($dto)]);
    }

    #[OA\Post(
        path: "/api/v1/login",
        description: "Авторизация происходит по email и паролю. Успешный ответ будет содержать Bearer токен, который нужно
        использовать для авторизации в других запросах. Токен будет действителен 60 минут. Для обновления токена следует ещё раз выполнить текущий запрос.",
        summary: "Login",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AuthenticateRequest")
        ),
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful login",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "ok"),
                        new OA\Property(
                            property: "content",
                            properties: [
                                new OA\Property(property: "token", type: "string", example: "54|zSA0wCsYADhnwylBCo86NPCKnz1jEYGns7cJbY8543e3b2ad")
                            ],
                            type: "object"
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError")),
            new OA\Response(response: 422, description: "Validation error", content: new OA\JsonContent(ref: "#/components/schemas/ValidationError"))
        ]
    )]
    public function login(AuthenticateRequest $request): JsonResponse
    {
        return $this->apiResponse([
            'token' => $this->userService->authorize($request->getEmail(), $request->getPassword())
        ]);
    }

    #[OA\Post(
        path: "/api/v1/logout",
        description: "Выход из системы. Удаляет текущий токен доступа пользователя.",
        summary: "Logout",
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(response: 200, description: "Successful", content: new OA\JsonContent(ref: "#/components/schemas/SuccessfulEmptyResponse")),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError"))
        ]
    )]
    public function logout(): JsonResponse
    {
        Auth::user()?->currentAccessToken()->delete();

        return $this->apiResponse();
    }

    #[OA\Post(
        path: "/api/v1/logout-all",
        description: "Выход из системы на всех устройствах. Удаляет все токены доступа пользователя.",
        summary: "Logout для всех устройств",
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(response: 200, description: "Successful", content: new OA\JsonContent(ref: "#/components/schemas/SuccessfulEmptyResponse")),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError"))
        ]
    )]
    public function logoutAllSessions(): JsonResponse
    {
        Auth::user()?->tokens()->delete();

        return $this->apiResponse();
    }

    #[OA\Post(
        path: "/api/v1/user/verify",
        description: "Подтверждение регистрации пользователя с помощью кода верификации.",
        summary: "Подтверждение регистрации",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/VerifyRegistrationRequest")
        ),
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(response: 200, description: "Successful", content: new OA\JsonContent(ref: "#/components/schemas/SuccessfulEmptyResponse")),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError")),
            new OA\Response(response: 422, description: "Validation error", content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")),
            new OA\Response(response: 404, description: "Not Found", content: new OA\JsonContent(ref: "#/components/schemas/NotFoundError"))
        ]
    )]
    public function verifyRegistration(VerifyRegistrationRequest $request): JsonResponse
    {
        $this->userService->verifyEmailAddress((int)$request->validated('code'), $request->user());

        return $this->apiResponse();
    }

    #[OA\Post(
        path: "/api/v1/user/verify/resend",
        description: "Повторная отправка кода верификации на email пользователя. Код будет отправлен на email, указанный при регистрации.",
        summary: "Повторная отправка кода верификации",
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(response: 200, description: "Successful", content: new OA\JsonContent(ref: "#/components/schemas/SuccessfulEmptyResponse")),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError")),
        ]
    )]
    public function verifyResend(Request $request): JsonResponse
    {
        $this->userService->sendVerifyNotification($request->user());

        return $this->apiResponse();
    }

    #[OA\Post(
        path: "/api/v1/reset-password/code",
        description: "Отправка кода для сброса пароля на email пользователя.",
        summary: "Отправка кода сброса пароля",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ResetPasswordRequest")
        ),
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Код сброса пароля успешно отправлен",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "ok"),
                        new OA\Property(property: "content", type: "array", items: new OA\Items()),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 422, description: "Validation error", content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")),
            new OA\Response(response: 404, description: "Not Found", content: new OA\JsonContent(ref: "#/components/schemas/NotFoundError"))
        ]
    )]
    public function resetPasswordCode(ResetPasswordRequest $request): JsonResponse
    {
        $this->userService->sendResetPasswordCode($request->validated('email'));

        return $this->apiResponse();
    }

    #[OA\Post(
        path: "/api/v1/reset-password/change",
        description: "Изменение пароля пользователя с использованием кода сброса.",
        summary: "Изменение пароля",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/ChangePasswordRequest")
        ),
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful response",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "ok"),
                        new OA\Property(property: "content", type: "array", items: new OA\Items()),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 422, description: "Validation error", content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")),
            new OA\Response(response: 404, description: "Not Found", content: new OA\JsonContent(ref: "#/components/schemas/NotFoundError"))
        ]
    )]
    public function resetPasswordChange(ChangePasswordRequest $request): JsonResponse
    {
        $this->userService->setPasswordByCode($request->validated('email'), $request->validated('code'), $request->validated('password'));

        return $this->apiResponse();
    }


    #[OA\Post(
        path: "/api/v1/user/password",
        summary: "Изменить пароль пользователя",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdatePasswordRequest")
        ),
        tags: ["Авторизация"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(response: 204, description: "Successful", content: new OA\JsonContent(ref: "#/components/schemas/SuccessfulEmptyResponse")),
            new OA\Response(response: 422, description: "Bad Request", content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError")),
        ]
    )]
    public function changePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $this->userService->changePassword($request->user(), $request->getPassword());

        return $this->apiResponse(code: 204);
    }
}
