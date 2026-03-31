<?php
	$_USER->restricted_area($user);

	$transaction_id = $_ROUTING["params"][1] ?? 0;
	$action = $_ROUTING["params"][2] ?? "list";

	$transaction = new App\Models\Transaction($_DB);
	$data = $transaction->get_transaction($transaction_id);

	$account = new App\Models\Account($_DB);
	$accounts = $account->get_accounts($user->id);

	$category = new App\Models\Category($_DB);
	$categories = $category->get_all_categories($user->id);
	$groupped_categories = $category->group_categories($categories);


	// Tworzenie transakcji
	if ($action === "create")
		require_once("transaction/create.php");

	// Edycja transakcji
	if ($action === "edit")
		require_once("transaction/edit.php");

	// Usuwanie transakcji
	if ($action === "delete" && isset($_GET["delete"]))
		require_once("transaction/delete.php");

	// Pojedyncza transakcja
	if ($action === "details")
		require_once("transaction/details.php");


	// Wczytanie widoku
	$modules = [
		VIEWS_DIR . "modules/transaction/{$action}.php"
	];

	require_once VIEWS_DIR . "global/page.php";