<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class UpdateUserDTO {
    private function __construct (
		public int    $id,
        public string $name,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
        $id			= $data["id"] ?? 0;
		$name		= $data["name"] ?? "";
		$errors		= [];

        if (empty($name))
            $errors["global"] = "Wymagana pola: imię.";

		if (empty($name))
			$errors["name"] = "Podaj swoje imię";

        return new self($id, $name, $errors);
    }
}