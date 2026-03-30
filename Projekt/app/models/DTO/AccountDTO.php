<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class AccountDTO {
    private function __construct (
        public string $name,
        public float  $balance,
        public string $currency,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
		$name		= $data["name"] ?? "";
		$balance	= floatval($data["balance"]) ?? 0;
        $currency	= $data["currency"] ?? "";
		$errors		= [];

        if (empty($name) || empty($currency))
            $errors["global"] = "Wymagana pola: nazwa, waluta rozliczeniowa";

		if (empty($name))
			$errors["name"] = "Podaj nazwę konta";

		if (empty($currency))
			$errors["currency"] = "Wybierz walutę rozliczeniową";

        return new self($name, $balance, $currency, $errors);
    }
}