<?php

	$data = [];
	$errors = [];

	// Dane do aktywacji
	$dto = App\Models\DTO\ActivateUserDTO::parse([
		"id" => intval($_ROUTING["params"][1] ?? 0),
		"token" => $_DB->sanitize($_ROUTING["params"][2] ?? "")
	]);
	$errors = $dto->errors;

	// Próba aktywacji
	if (empty($errors)) {
		$res = $_USER->activate($dto);
		$errors = $res["errors"] ?? [];
	}

	// Wczytanie widoku
	$modules = [
		VIEWS_DIR . "modules/user/activation.php"
	];

	require_once VIEWS_DIR . "global/page.php";