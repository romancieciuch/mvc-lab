<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class PasswordRecoveryDTO {
    private function __construct (
        public string $email,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
        $email		= $data["email"] ?? "";
		$errors		= [];

        if (empty($email))
            $errors["global"] = "Wymagana pola: e-mail";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			$errors["email"] = "Niepoprawny adres e-mail";

        return new self($email, $errors);
    }
}