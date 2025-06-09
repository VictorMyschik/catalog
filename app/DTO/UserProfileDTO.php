<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class UserProfileDTO implements \JsonSerializable
{
    public function __construct(
        public ?string $email,
        public ?string $name,
        public ?string $password = null,
    ) {}

    public function jsonSerialize(): array
    {
        $out = [];

        if ($this->email) {
            $out['email'] = $this->email;
        }

        if ($this->name) {
            $out['name'] = $this->name;
        }

        if ($this->password) {
            $out['password'] = $this->password;
        }

        return $out;
    }
}
