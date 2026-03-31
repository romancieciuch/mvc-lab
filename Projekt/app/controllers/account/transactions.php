<?php

	$transactions = $_DB->query(
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
			"user_id" => $user->id,
			"limit" => $limit,
			"offset" => $offset
		]
	);