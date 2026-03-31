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
		var_dump("INSERT INTO transactions (account_id, category_id, amount, description, transaction_date)
				VALUES (:account_id, :category_id, :amount, :description, :transaction_date)");

			var_dump([
				"account_id" => $dto->account_id,
				"category_id" => $dto->category_id,
				"amount" => $dto->amount,
				"description" => $dto->description,
				"transaction_date" => $dto->transaction_date
			]);

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
			"SELECT * FROM transactions
				WHERE id = :transaction_id
					LIMIT 1",
			[
				"transaction_id" => $transaction_id
			]
		);
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

	public function delete_transaction (int $account_id, int $transaction_id) {
		return $this->db->query(
			"DELETE FROM transactions
				WHERE id = :transaction_id AND account_id = :account_id
					LIMIT 1",
			[
				"id" => $transaction_id,
				"account_id" => $account_id
			]
		);
	}
}