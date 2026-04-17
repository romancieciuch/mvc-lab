<?php

	$_USER->restricted_area($user);

	$category_id = $_ROUTING["params"][1] ?? 0;
	$action = $_ROUTING["params"][2] ?? "list";

	if ($action !== "create")
		$_USER->verify_user($user->id, 0, 0, $category_id);

	$account = new App\Models\Account($_DB);
	$accounts = $account->get_accounts($user->id);

	$category = new App\Models\Category($_DB);
	$data = $category->get_category($category_id);

	$category_types = [
		"default"	=> "Domyślny",
		"income"	=> "Zarobki",
		"expense"	=> "Wydatki",
		"tax"		=> "Podatki"
	];

	$prev_page = "/dashboard/";
	if (!empty($_GET["account-id"]))
		$prev_page = "/account/" . intval($_GET["account-id"]) . "/transactions/";


	// Tworzenie kategorii
	if ($action === "create")
		require_once("category/create.php");

	// Edycja kategorii
	if ($action === "edit")
		require_once("category/edit.php");

	// Usuwanie kategorii
	if ($action === "delete" && isset($_GET["delete"]))
		require_once("category/delete.php");


	// Wczytanie widoku
	$modules = [
		VIEWS_DIR . "modules/category/{$action}.php"
	];

	require_once VIEWS_DIR . "global/page.php";