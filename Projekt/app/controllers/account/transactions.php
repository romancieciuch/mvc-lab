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

	$history = $account->get_history($user->id, $account_id, $search["date_from"], $search["date_to"]);
	$history = array_reverse($history);

	if (empty($_GET["search"]) && empty($_GET["amount-min"]) && empty($_GET["amount-max"]) && empty($_GET["category-id"])) {
		$chart = new App\Models\Chart(["currency" => $data[0]["currency"]]);
		$chart_html = $chart->draw($history, "log_date", "balance");
	}

	$summary = $account->get_period_summary($user->id, $account_id, $search["date_from"], $search["date_to"]);
	$pln_value = $_APP->exchange($transactions["total_amount"], $data[0]["currency"] ?? "PLN", "PLN");


	$autotaxes_month = date("Y-m");
	if (!empty($search["date_from"]))
		$autotaxes_month = date("Y-m", strtotime($search["date_from"]));

	$autotaxes_month_formatted = date("m/Y", strtotime($autotaxes_month));

	$transactions_no_pagination = $transaction->search_transactions(
		$account_id,
		$user->id,
		PHP_INT_MAX,
		0,
		[
			...$search,
			"date_from" => date("Y-m-01", strtotime($autotaxes_month)),
			"date_to" => date("Y-m-t", strtotime($autotaxes_month)),
		]
	);
	$taxes = $account->calculate_taxes($transactions_no_pagination["data"]);

	// Opcje użytkownika
	$settings = $_USER->get_user_settings($user->id);