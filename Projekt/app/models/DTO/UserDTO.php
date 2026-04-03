<?php

declare(strict_types=1);
namespace App\Models\DTO;

use InvalidArgumentException;

readonly class UserDTO {
    private function __construct (
		public bool   $logged_in,
		public bool   $logged_in_2FA,
		public int    $id,
        public string $name,
        public string $email,
		public bool   $two_factor_auth,
		public string $created_at
    ) {}

	public static function parse (array $data = []) : self {
		$logged_in			= $data["logged_in"] ?? false;
		$logged_in_2FA		= $data["logged_in_2FA"] ?? false;
		$id					= $data["id"] ?? 0;
        $name				= $data["name"] ?? "";
		$email				= $data["email"] ?? "";
		$two_factor_auth	= $data["two_factor_auth"] ?? false;
		$created_at			= $data["created_at"] ?? "";

        return new self($logged_in, $logged_in_2FA, $id, $name, $email, $two_factor_auth, $created_at);
    }
}