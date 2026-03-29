<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class UpdateUserDTO {
    private function __construct (
		public int    $id,
        public string $name,
        public string $password,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
        $id			= $data["id"] ?? 0;
		$name		= $data["name"] ?? "";
        $password	= $data["password"] ?? "";
		$password2	= $data["password2"] ?? "";
		$errors		= [];

        if (empty($name))
            $errors["global"] = "Wymagana pola: imię.";

		if (empty($name))
			$errors["name"] = "Podaj swoje imię";

		if (!empty($password) && !empty($password2)) {
			if (strlen($password) < 8)
				$errors["password"] = "Hasło powinno mieć przynajmniej 8 znaków";

			if ($password !== $password2)
				$errors["password2"] = "Hasła nie są jednakowe";
		}

        return new self($id, $name, $password, $errors);
    }
}