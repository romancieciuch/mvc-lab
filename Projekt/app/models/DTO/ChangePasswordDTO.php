<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class ChangePasswordDTO {
    private function __construct (
		public int    $id,
        public string $password,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
        $id			= $data["id"] ?? 0;
        $password	= $data["password"] ?? "";
		$password2	= $data["password2"] ?? "";
		$errors		= [];

		if (!empty($password) && !empty($password2)) {
			if (strlen($password) < 8)
				$errors["password"] = "Hasło powinno mieć przynajmniej 8 znaków";

			if ($password !== $password2)
				$errors["password2"] = "Hasła nie są jednakowe";
		}

        return new self($id, $password, $errors);
    }
}