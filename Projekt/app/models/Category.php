<?php

declare(strict_types=1);
namespace App\Models;

use App\Models\DTO\CategoryDTO;

class Category {
	private DB $db;

	public function __construct(DB $db) {
		$this->db = $db;
	}

	public function create_category (CategoryDTO $dto) {
		return $this->db->query(
			"INSERT INTO categories (account_id, name, color)
				VALUES (:account_id, :name, :color)",
			[
				"account_id" => $dto->account_id,
				"name" => $dto->name,
				"color" => $dto->color
			]
		);
	}

	public function get_category (int $category_id = 0) {
		return $this->db->query(
			"SELECT c.id, c.name, c.color, c.created_at,
					a.id AS account_id, a.name AS account_name
				FROM categories c, accounts a
					WHERE c.id = :category_id AND c.account_id = a.id
						LIMIT 1",
			[
				"category_id" => $category_id
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

	public function get_account_categories (int $account_id = 0) {
		return $this->db->query(
			"SELECT * FROM categories
				WHERE account_id = :account_id
					ORDER BY account_id ASC",
			[
				"account_id" => $account_id
			]
		);
	}

	public function group_categories (array $categories = []) {
		$json = [];
		foreach ($categories as $category)
			$json[$category["account_id"]][] = ["id" => $category["id"], "name" => $category["name"]];

		return $json;
	}

	public function update_category (int $category_id, CategoryDTO $dto) {
		return $this->db->query(
			"UPDATE categories
				SET name = :name, color = :color, account_id = :account_id
					WHERE id = :category_id
						LIMIT 1",
			[
				"category_id" => $category_id,
				"name" => $dto->name,
				"account_id" => $dto->account_id,
				"color" => $dto->color
			]
		);
	}

	public function delete_category (int $category_id = 0) {
		return $this->db->query(
			"DELETE FROM categories
				WHERE id = :category_id
					LIMIT 1",
			[
				"category_id" => $category_id
			]
		);
	}
}