<?php

declare(strict_types=1);

namespace App\Domain\Auth\DTOs;

use App\Domain\Auth\Enums\UserRole;

final readonly class RegisterDTO
{
    public function __construct(
        public string   $name,
        public string   $email,
        public string   $password,
        public UserRole $role,
        public ?int     $supervisorId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name:         $data['name'],
            email:        $data['email'],
            password:     $data['password'],
            role:         UserRole::from($data['role']),
            supervisorId: $data['supervisor_id'] ?? null,
        );
    }
}
