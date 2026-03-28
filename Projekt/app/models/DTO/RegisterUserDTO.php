<?php

declare(strict_types=1);
namespace App\Models\DTO;

use InvalidArgumentException;

readonly class RegisterUserDTO {
    private function __construct (
        public string $name,
        public string $email,
        public string $password
    ) {}

	public static function parse (array $data = []) : self {
        $name		= $data["name"] ?? "";
        $email		= $data["email"] ?? "";
        $password	= $data["password"] ?? "";

        if (empty($name) || empty($email) || empty($password))
            throw new InvalidArgumentException("Required fields: name, email, password");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new InvalidArgumentException("Invalid e-mail format");

        if (strlen($password) < 8)
            throw new InvalidArgumentException("Password is too short");

        return new self($name, $email, $password);
    }
}