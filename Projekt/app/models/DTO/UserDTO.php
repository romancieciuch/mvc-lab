<?php

declare(strict_types=1);
namespace App\Models\DTO;

use InvalidArgumentException;

readonly class UserDTO {
    private function __construct (
        public string $name,
        public string $email
    ) {}

	public static function parse (array $data = []) : self {
        $name		= $data["name"] ?? "";
        $email		= $data["email"] ?? "";

        return new self($name, $email);
    }
}