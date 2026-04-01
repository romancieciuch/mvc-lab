<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class CategoryDTO {
    private function __construct (
        public int		$account_id,
		public string	$name,
		public string	$color,
		public array	$errors
    ) {}

	public static function parse (array $data = []) : self {
		$account_id = intval($data["account_id"] ?? 0);
		$name		= $data["name"] ?? "";
		$color		= $data["color"] ?? "";
		$errors		= [];

        if (empty($name) || empty($account_id))
            $errors["global"] = "Wymagana pola: nazwa kategorii, konto";

		if (empty($name))
			$errors["name"] = "Podaj nazwę kategorii";

		if (empty($account_id))
			$errors["account_id"] = "Wybierz konto";

        return new self($account_id, $name, $color, $errors);
    }
}