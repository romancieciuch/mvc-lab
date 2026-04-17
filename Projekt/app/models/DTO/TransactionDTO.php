<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class TransactionDTO {
    private function __construct (
        public int		$account_id,
		public int|null	$category_id,
        public float	$amount,
		public float	$vat_rate,
		public float	$income_tax_rate,
		public string	$name,
        public string	$description,
		public string	$transaction_date,
		public array	$errors
    ) {}

	public static function parse (array $data = []) : self {
		$account_id 		= intval($data["account_id"] ?? 0);
		$category_id 		= empty($data["category_id"]) ? null : intval($data["category_id"]);
		$amount				= floatval($data["amount"]) ?? 0;
		$vat_rate			= floatval($data["vat_rate"]) ?? 0;
		$income_tax_rate	= floatval($data["income_tax_rate"]) ?? 0;
		$name				= $data["name"] ?? "";
		$description		= $data["description"] ?? "";
        $transaction_date	= $data["transaction_date"] ?? date("Y-m-d");
		$errors				= [];

        if (empty($account_id) || empty($amount) || empty($name))
            $errors["global"] = "Wymagana pola: nazwa, kwota";

		if (empty($account_id))
			$errors["amount"] = "Wybierz konto, do którego przypisać transakcję";

		if (empty($amount))
			$errors["amount"] = "Podaj kwotę transakcji";

		if (empty($name))
			$errors["name"] = "Podaj nazwę transakcji";

        return new self($account_id, $category_id, $amount, $vat_rate, $income_tax_rate, $name, $description, $transaction_date, $errors);
    }
}