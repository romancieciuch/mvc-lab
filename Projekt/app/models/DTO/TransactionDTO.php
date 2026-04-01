<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class TransactionDTO {
    private function __construct (
        public int		$account_id,
		public int|null	$category_id,
        public float	$amount,
        public string	$description,
		public string	$transaction_date,
		public array	$errors
    ) {}

	public static function parse (array $data = []) : self {
		$account_id 		= intval($data["account_id"] ?? 0);
		$category_id 		= empty($data["category_id"]) ? null : intval($data["category_id"]);
		$amount				= floatval($data["amount"]) ?? 0;
		$description		= $data["description"] ?? "";
        $transaction_date	= $data["transaction_date"] ?? date("Y-m-d");
		$errors				= [];

        if (empty($account_id) || empty($amount) || empty($description))
            $errors["global"] = "Wymagana pola: nazwa, kwota";

		if (empty($account_id))
			$errors["amount"] = "Wybierz konto, do którego przypisać transakcję";

		if (empty($amount))
			$errors["amount"] = "Podaj kwotę transakcji";

		if (empty($description))
			$errors["description"] = "Podaj nazwę transakcji";

        return new self($account_id, $category_id, $amount, $description, $transaction_date, $errors);
    }
}