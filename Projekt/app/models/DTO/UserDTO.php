<?php

declare(strict_types=1);
namespace App\Models\DTO;

use InvalidArgumentException;

readonly class UserDTO {
    private function __construct (
		public bool $logged_in,
		public int $id,
        public string $name,
        public string $email,
		public string $created_at
    ) {}

	public static function parse (array $data = []) : self {
		$logged_in	= $data["logged_in"] ?? false;
		$id			= $data["id"] ?? 0;
        $name		= $data["name"] ?? "";
		$email		= $data["email"] ?? "";
		$created_at	= $data["created_at"] ?? "";

        return new self($logged_in, $id, $name, $email, $created_at);
    }
}