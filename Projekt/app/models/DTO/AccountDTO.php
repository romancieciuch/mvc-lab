<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class AccountDTO {
    private function __construct (
        public string $name,
        public string $currency,
		public int    $priority,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
		$name		= $data["name"] ?? "";
        $currency	= $data["currency"] ?? "";
		$priority	= intval($data["priority"] ?? 0);
		$errors		= [];

        if (empty($name) || empty($currency))
            $errors["global"] = "Wymagana pola: nazwa, waluta rozliczeniowa";

		if (empty($name))
			$errors["name"] = "Podaj nazwę konta";

		if (empty($currency))
			$errors["currency"] = "Wybierz walutę rozliczeniową";

		if ($priority < 0 || $priority > 100)
			$errors["priority"] = "Ustal priorytet od 0 do 100";

        return new self($name, $currency, $priority, $errors);
    }
}
