<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class AccountDTO {
    private function __construct (
        public string $name,
        public string $currency,
		public int    $priority,
		public bool   $include_in_total,
		public string $account_type,
		public array  $errors
    ) {}

	public static function parse (array $data = []) : self {
		$name			  = $data["name"] ?? "";
        $currency		  = $data["currency"] ?? "";
		$priority		  = intval($data["priority"] ?? 0);
		$include_in_total = boolval($data["include_in_total"] ?? true);
		$account_type     = $data["account_type"] ?? "personal";
		$errors			  = [];

        if (empty($name) || empty($currency))
            $errors["global"] = "Wymagana pola: nazwa, waluta rozliczeniowa";

		if (empty($name))
			$errors["name"] = "Podaj nazwę konta";

		if (empty($currency))
			$errors["currency"] = "Wybierz walutę rozliczeniową";

		if ($priority < 0 || $priority > 100)
			$errors["priority"] = "Ustal priorytet od 0 do 100";

		if (!in_array($account_type, ["personal", "business"]))
			$errors["account_type"] = "Niepoprawny typ konta";

        return new self($name, $currency, $priority, $include_in_total, $account_type, $errors);
    }
}
