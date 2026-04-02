<?php

	$search = [
		"search" => $_GET["search"] ?? "",
		"date_from" => $_GET["date-from"] ?? "",
		"date_to" => $_GET["date-to"] ?? "",
		"amount_min" => $_GET["amount-min"] ?? "",
		"amount_max" => $_GET["amount-max"] ?? "",
		"category_id" => $_GET["category-id"] ?? ""
	];

	$transaction = new App\Models\Transaction($_DB);
	$transactions = $transaction->search_transactions(
		$account_id,
		$user->id,
		$pagination["limit"],
		$pagination["offset"],
		$search
	);