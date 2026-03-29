<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class LoginUserDTO {
    private function __construct (
        public string $email,
        public string $password,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
        $email		= $data["email"] ?? "";
        $password	= $data["password"] ?? "";
		$errors		= [];

        if (empty($email) || empty($password))
            $errors["global"] = "Wymagana pola: e-mail, hasło";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			$errors["email"] = "Niepoprawny adres e-mail";

        if (strlen($password) < 8)
            $errors["password"] = "Hasło powinno mieć przynajmniej 8 znaków";

        return new self($email, $password, $errors);
    }
}