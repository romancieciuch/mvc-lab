<?php

	$account_id = $_ROUTING["params"][1] ?? 0;
	$action = $_ROUTING["params"][2] ?? "list";

	$account = new App\Models\Account($_DB);
	$data = $account->get_account($user->id, $account_id);

	$page = $_GET["page"] ?? 1;
	$limit = $_GET["per-page"] ?? 10;
	$offset = ($page - 1) * $limit;


	// Tworzenie konta
	if ($action === "create")
		require_once("account/create.php");

	// Edycja konta
	if ($action === "edit")
		require_once("account/edit.php");

	// Usuwanie konta
	if ($action === "delete" && isset($_GET["delete"]))
		require_once("account/delete.php");

	// Lista transakcji
	if ($action === "transactions")
		require_once("account/transactions.php");


	// Wczytanie widoku
	$modules = [
		VIEWS_DIR . "modules/account/{$action}.php"
	];

	require_once VIEWS_DIR . "global/page.php";