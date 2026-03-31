<?php

declare(strict_types=1);
namespace App\Models;

use App\Models\DTO\AccountDTO;

class Category {
	private DB $db;

	public function __construct(DB $db) {
		$this->db = $db;
	}

	public function create_account (int $user_id, AccountDTO $dto) {
		return $this->db->query(
			"INSERT INTO accounts (user_id, name, balance, currency)
				VALUES (:user_id, :name, :balance, :currency)",
			[
				"user_id" => $user_id,
				"name" => $dto->name,
				"balance" => $dto->balance,
				"currency" => $dto->currency
			]
		);
	}

	public function get_account (int $user_id = 0, int $account_id = 0) {
		return $this->db->query(
			"SELECT * FROM accounts
				WHERE id = :account_id AND user_id = :user_id
					LIMIT 1",
			[
				"account_id" => $account_id,
				"user_id" => $user_id
			]
		);
	}

	public function get_all_categories (int $user_id = 0) {
		return $this->db->query(
			"SELECT * FROM categories
				WHERE account_id IN (SELECT id FROM accounts WHERE user_id = :user_id)
					ORDER BY account_id ASC",
			[
				"user_id" => $user_id
			]
		);
	}

	public function group_categories (array $categories = []) {
		$json = [];
		foreach ($categories as $category)
			$json[$category["account_id"]][] = ["id" => $category["id"], "name" => $category["name"]];

		return $json;
	}

	public function update_account (int $user_id, int $account_id, AccountDTO $dto) {
		return $this->db->query(
			"UPDATE accounts
				SET name = :name, balance = :balance, currency = :currency
					WHERE id = :account_id AND user_id = :user_id
						LIMIT 1",
			[
				"user_id" => $user_id,
				"account_id" => $account_id,
				"name" => $dto->name,
				"balance" => $dto->balance,
				"currency" => $dto->currency
			]
		);
	}

	public function delete_account (int $user_id = 0, int $account_id = 0) {
		return $this->db->query(
			"DELETE FROM accounts
				WHERE id = :account_id AND user_id = :user_id
					LIMIT 1",
			[
				"account_id" => $account_id,
				"user_id" => $user_id
			]
		);
	}
}