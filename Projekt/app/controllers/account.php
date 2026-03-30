<?php

	$account_id = $_ROUTING["params"][1] ?? 0;
	$action = $_ROUTING["params"][2] ?? "list";

	$account = new App\Models\Account($_DB);
	$data = $account->get_account($user->id, $account_id);


	// Tworzenie konta
	if ($action === "create")
		require_once("account/create.php");

	// Edycja konta
	if ($action === "edit")
		require_once("account/edit.php");

	// Usuwanie konta
	if ($action === "delete" && isset($_GET["delete"])) {
		$deleted = $account->delete_account($user->id, $account_id);
		$data = [];
	}


	// Wczytanie widoku
	$modules = [
		VIEWS_DIR . "modules/account/{$action}.php"
	];

	require_once VIEWS_DIR . "global/page.php";