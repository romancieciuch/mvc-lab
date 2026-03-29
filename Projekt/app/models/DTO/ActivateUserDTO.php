<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class ActivateUserDTO {
    private function __construct (
        public int    $id,
        public string $token,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
        $id		= $data["id"] ?? 0;
        $token	= $data["token"] ?? "";
		$errors	= [];

        if (empty($id) || empty($token))
            $errors["global"] = "Wymagana pola: id użytkownika, token";

        return new self($id, $token, $errors);
    }
}