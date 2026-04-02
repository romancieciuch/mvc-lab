<?php

declare(strict_types=1);
namespace App\Models;

use App\Models\DTO\AccountDTO;

class Account {
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
		$categories = $this->db->query(
			"SELECT id, name, color FROM categories
				WHERE account_id = :account_id",
			[
				"account_id" => $account_id
			]
		);

		$res = $this->db->query(
			"SELECT * FROM accounts
				WHERE id = :account_id AND user_id = :user_id
					LIMIT 1",
			[
				"account_id" => $account_id,
				"user_id" => $user_id
			]
		);

		$res["categories"] = $categories;

		return $res;
	}

	public function get_accounts (int $user_id = 0) {
		return $this->db->query(
			"SELECT
				a.id,
				a.name,
				a.balance,
				a.currency,
				a.created_at,
				COALESCE(AVG(t.amount), 0) AS avg_transaction
			FROM accounts a
			LEFT JOIN transactions t ON a.id = t.account_id
			WHERE a.user_id = :user_id
			GROUP BY a.id",
			[
				"user_id" => $user_id
			]
		);
	}

	public function get_accounts_summary (int $user_id = 0) : array {
		$txSql = "SELECT
					COUNT(t.id) AS total_transactions,
					AVG(t.amount) AS avg_amount
				FROM transactions t
				INNER JOIN accounts a ON t.account_id = a.id
				WHERE a.user_id = :user_id";

		$txStats = $this->db->query($txSql, ["user_id" => $user_id]);

		$balanceSql = "SELECT
						SUM(balance) AS total_balance
					FROM accounts
					WHERE user_id = :user_id";

		$balanceStats = $this->db->query($balanceSql, ["user_id" => $user_id]);

		return [
			"total_transactions" => $txStats[0]["total_transactions"] ?? 0,
			"avg_amount"         => $txStats[0]["avg_amount"] ?? 0.00,
			"total_balance"      => $balanceStats[0]["total_balance"] ?? 0.00
		];
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

	public function has_different_currencies (array $accounts = []) : bool {
		$default_currency = $accounts[0]["currency"] ?? "PLN";

		foreach ($accounts as $account)
			if ($account["currency"] !== $default_currency)
				return true;

		return false;
	}
}