<?php

declare(strict_types=1);
namespace App\Models\DTO;

use InvalidArgumentException;

readonly class LoginUserDTO {
    private function __construct (
        public string $email,
        public string $password
    ) {}

	public static function parse (array $data = []) : self {
        $email		= $data["email"] ?? "";
        $password	= $data["password"] ?? "";

        if (empty($email) || empty($password))
            throw new InvalidArgumentException("Required fields: name, email, password");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new InvalidArgumentException("Invalid e-mail format");

        if (strlen($password) < 8)
            throw new InvalidArgumentException("Password is too short");

        return new self($email, $password);
    }
}