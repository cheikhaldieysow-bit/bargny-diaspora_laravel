<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public int $id,
        public ?int $role_id,
        public string $name,
        public string $email,
        public ?string $email_verified_at,
        public ?string $address,
        public ?string $phone,
        public ?string $created_at,
        public ?string $updated_at
    ) {}
}
