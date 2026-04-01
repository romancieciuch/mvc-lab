<?php

declare(strict_types=1);
namespace App\Models;

use App\Models\DTO\TransactionDTO;

class Transaction {
	private DB $db;

	public function __construct(DB $db) {
		$this->db = $db;
	}

	public function create_transaction (TransactionDTO $dto) {
		return $this->db->query(
			"INSERT INTO transactions (account_id, category_id, amount, description, transaction_date)
				VALUES (:account_id, :category_id, :amount, :description, :transaction_date)",
			[
				"account_id" => $dto->account_id,
				"category_id" => $dto->category_id,
				"amount" => $dto->amount,
				"description" => $dto->description,
				"transaction_date" => $dto->transaction_date
			]
		);
	}

	public function get_transaction (int $transaction_id) {
		return $this->db->query(
			"SELECT t.amount, t.description, t.transaction_date,
					c.name AS category_name, c.color,
					a.name AS account_name, a.balance, a.currency, a.id AS account_id
				FROM transactions t
					INNER JOIN accounts a ON t.account_id = a.id
					LEFT JOIN categories c ON t.category_id = c.id
						WHERE t.id = :transaction_id
							LIMIT 1",
			[
				"transaction_id" => $transaction_id
			]
		);
	}

	public function get_transactions (int $account_id, int $user_id, int $limit, int $offset) {
		$total = $this->db->query("
			SELECT COUNT(t.id) AS total
				FROM transactions t
					INNER JOIN accounts a ON t.account_id = a.id
						WHERE t.account_id = :account_id
							AND a.user_id = :user_id",
			[
				"account_id" => $account_id,
				"user_id" => $user_id
			]
		);

		$data = $this->db->query(
			"SELECT
				t.id AS transaction_id,
					t.amount,
					t.description as name,
					t.transaction_date,
					c.id AS category_id,
					c.name AS category_name,
					c.color AS category_color,
					a.currency
				FROM transactions t
					INNER JOIN accounts a ON t.account_id = a.id
					LEFT JOIN categories c ON t.category_id = c.id
						WHERE t.account_id = :account_id
							AND a.user_id = :user_id
								ORDER BY t.transaction_date DESC, t.id DESC
									LIMIT :limit OFFSET :offset",
			[
				"account_id" => $account_id,
				"user_id" => $user_id,
				"limit" => $limit,
				"offset" => $offset
			]
		);

		return [
			"total" => $total[0]["total"],
			"data" => $data
		];
	}

	public function update_transaction (int $transaction_id, TransactionDTO $dto) {
		return $this->db->query(
			"UPDATE transactions
				SET account_id = :account_id, category_id = :category_id,
					amount = :amount, description = :description, transaction_date = :transaction_date
						WHERE id = :transaction_id
							LIMIT 1",
			[
				"transaction_id" => $transaction_id,
				"account_id" => $dto->account_id,
				"category_id" => $dto->category_id,
				"amount" => $dto->amount,
				"description" => $dto->description,
				"transaction_date" => $dto->transaction_date
			]
		);
	}

	public function delete_transaction (int $transaction_id) {
		return $this->db->query(
			"DELETE FROM transactions
				WHERE id = :transaction_id
					LIMIT 1",
			[
				"transaction_id" => $transaction_id
			]
		);
	}
}