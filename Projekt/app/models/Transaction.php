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
			"INSERT INTO transactions (account_id, category_id, amount, vat_rate, income_tax_rate, name, description, transaction_date)
				VALUES (:account_id, :category_id, :amount, :vat_rate, :income_tax_rate, :name, :description, :transaction_date)",
			[
				"account_id" => $dto->account_id,
				"category_id" => $dto->category_id,
				"amount" => $dto->amount,
				"vat_rate" => $dto->vat_rate,
				"income_tax_rate" => $dto->income_tax_rate,
				"name" => $dto->name,
				"description" => $dto->description,
				"transaction_date" => $dto->transaction_date
			]
		);
	}

	public function get_transaction (int $transaction_id) {
		return $this->db->query(
			"SELECT t.amount, t.vat_rate, t.income_tax_rate, t.name, t.description, t.transaction_date,
					c.id AS category_id, c.name AS category_name, c.color,
					a.name AS account_name, account_type, a.balance, a.currency, a.id AS account_id
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

	public function get_history (int $transaction_id) {
		return $this->db->query(
			"SELECT amount, log_date
				FROM transaction_history
					WHERE transaction_id = :transaction_id
						ORDER BY log_date DESC",
			[
				"transaction_id" => $transaction_id
			]
		);
	}

	public function calculate_history (array $data = [], bool $desc = true) {
		if ($desc) $reversed = array_reverse($data);

		$previous_balance = null;

		foreach ($reversed as &$row) {
			if ($previous_balance === null)
				$row["change"] = 0.00;
			else
				$row["change"] = round($row["amount"] - $previous_balance, 2);

			// Klasy CSS
			$row["change_class"] = "is-neutral";
			if ($row["change"] > 0) $row["change_class"] = "is-positive";
			if ($row["change"] < 0) $row["change_class"] = "is-negative";

			// Znak z przodu
			$row["change_with_sign"] = $row["change"];
			if ($row["change"] >  0) $row["change_with_sign"] = "+{$row["change_with_sign"]}";

			$previous_balance = $row["amount"];
		}

		unset($row);

		if ($desc) $reversed = array_reverse($reversed);
		return $reversed;
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
					t.name,
					t.description,
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

	public function search_transactions (int $account_id, int $user_id, int $limit, int $offset, array $search = []) {
        $whereSql = " WHERE t.account_id = :account_id AND a.user_id = :user_id";
        $params = [
            "account_id" => $account_id,
            "user_id" => $user_id
        ];

        if (!empty($search['search'])) {
            $whereSql .= " AND (t.name LIKE :search_desc OR c.name LIKE :search_cat)";

            $searchTerm = '%' . $search['search'] . '%';
            $params['search_desc'] = $searchTerm;
            $params['search_cat']  = $searchTerm;
        }

        if (!empty($search['date_from'])) {
            $whereSql .= " AND t.transaction_date >= :date_from";
            $params['date_from'] = $search['date_from'];
        }

        if (!empty($search['date_to'])) {
            $whereSql .= " AND t.transaction_date <= :date_to";
            $params['date_to'] = $search['date_to'];
        }

        if (isset($search['amount_min']) && $search['amount_min'] !== '') {
            $whereSql .= " AND ABS(t.amount) >= :amount_min";
            $params['amount_min'] = (float)$search['amount_min'];
        }

        if (isset($search['amount_max']) && $search['amount_max'] !== '') {
            $whereSql .= " AND ABS(t.amount) <= :amount_max";
            $params['amount_max'] = (float)$search['amount_max'];
        }

		if (!empty($search['category_id'])) {
            $whereSql .= " AND (t.category_id = :category_id)";
			$params['category_id'] = $search['category_id'];
        }

        $countSql = "SELECT
					COUNT(t.id) AS total,
					SUM(t.amount) AS total_amount,
					AVG(t.amount) AS avg_amount
                    FROM transactions t
                    INNER JOIN accounts a ON t.account_id = a.id
                    LEFT JOIN categories c ON t.category_id = c.id"
                    . $whereSql;

        $total = $this->db->query($countSql, $params);

        $dataSql = "SELECT
                        t.id AS transaction_id,
                        t.amount, t.vat_rate, t.income_tax_rate,
                        t.name,
						t.description,
                        t.transaction_date,
                        c.id AS category_id,
                        c.name AS category_name,
						c.category_type,
                        c.color AS category_color,
                        a.currency
                    FROM transactions t
                    INNER JOIN accounts a ON t.account_id = a.id
                    LEFT JOIN categories c ON t.category_id = c.id"
                    . $whereSql . "
                    ORDER BY t.transaction_date DESC, t.id DESC
                    LIMIT :limit OFFSET :offset";

        $dataParams = $params;
        $dataParams['limit'] = $limit;
        $dataParams['offset'] = $offset;

        $data = $this->db->query($dataSql, $dataParams);

        return [
            "total" => $total[0]["total"] ?? 0,
            "total_amount" => $total[0]["total_amount"] ?? 0.00,
			"avg_amount" => $total[0]["avg_amount"] ?? 0.00,
            "data" => $data
        ];
    }

	public function update_transaction (int $transaction_id, TransactionDTO $dto) {
		$res = $this->db->query(
			"UPDATE transactions
				SET account_id = :account_id, category_id = :category_id,
					amount = :amount, vat_rate = :vat_rate, income_tax_rate = :income_tax_rate,
					name = :name, description = :description, transaction_date = :transaction_date
						WHERE id = :transaction_id
							LIMIT 1",
			[
				"transaction_id" => $transaction_id,
				"account_id" => $dto->account_id,
				"category_id" => $dto->category_id,
				"amount" => $dto->amount,
				"vat_rate" => $dto->vat_rate,
				"income_tax_rate" => $dto->income_tax_rate,
				"name" => $dto->name,
				"description" => $dto->description,
				"transaction_date" => $dto->transaction_date
			]
		);

		if (empty($res))
			$errors["global"] = "Nie wprowadzono żadnych zmian.";

		return [
			"success" => (bool) $res,
			"errors" => $errors ?? []
		];
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