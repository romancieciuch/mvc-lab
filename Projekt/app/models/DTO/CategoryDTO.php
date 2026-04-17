<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class CategoryDTO {
    private function __construct (
        public int		$account_id,
		public string	$name,
		public string	$category_type,
		public string	$color,
		public array	$errors
    ) {}

	public static function parse (array $data = []) : self {
		$account_id = intval($data["account_id"] ?? 0);
		$name		= $data["name"] ?? "";
		$color		= $data["color"] ?? "";
		$category_type = $data["category_type"] ?? "default";
		$errors		= [];

        if (empty($name) || empty($account_id))
            $errors["global"] = "Wymagana pola: nazwa kategorii, konto";

		if (empty($name))
			$errors["name"] = "Podaj nazwę kategorii";

		if (empty($account_id))
			$errors["account_id"] = "Wybierz konto";

		if (!in_array($category_type, ["default", "income", "expense", "tax"]))
			$errors["category_type"] = "Wybierz rodzaj kategorii";

        return new self($account_id, $name, $category_type, $color, $errors);
    }
}