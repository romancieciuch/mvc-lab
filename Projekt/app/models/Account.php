<?php

declare(strict_types=1);
namespace App\Models;

class Account {
	private DB $db;

	public function __construct(DB $db) {
		$this->db = $db;
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