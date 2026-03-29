<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class RegisterUserDTO {
    private function __construct (
        public string $name,
        public string $email,
        public string $password,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
        $name		= $data["name"] ?? "";
        $email		= $data["email"] ?? "";
        $password	= $data["password"] ?? "";
		$password2	= $data["password2"] ?? "";
		$errors		= [];

        if (empty($name) || empty($email) || empty($password) || empty($password2))
            $errors["global"] = "Wymagana pola: imię, e-mail, hasło, powtórzenie hasła";

		if (empty($name))
			$errors["name"] = "Podaj swoje imię";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			$errors["email"] = "Niepoprawny adres e-mail";

        if (strlen($password) < 8)
            $errors["password"] = "Hasło powinno mieć przynajmniej 8 znaków";

		if ($password !== $password2)
			$errors["password2"] = "Hasła nie są jednakowe";

        return new self($name, $email, $password, $errors);
    }
}