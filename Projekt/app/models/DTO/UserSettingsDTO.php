<?php

declare(strict_types=1);
namespace App\Models\DTO;

readonly class UserSettingsDTO {
    private function __construct (
        public string	$world_ends,
		public float	$monthly_expenses,
		public array	$fields,
		public array	$errors
    ) {}

	public static function parse (array $data = []) : self {
        $world_ends			= $data["world_ends"] ?? date("Y-m-d");
		$monthly_expenses	= floatval($data["monthly_expenses"]) ?? 0;

		$fields	= [
			"world_ends" => $world_ends,
			"monthly_expenses" => $monthly_expenses
		];

		$errors = [];

        return new self($world_ends, $monthly_expenses, $fields, $errors);
    }
}