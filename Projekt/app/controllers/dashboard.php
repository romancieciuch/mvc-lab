<?php
	$_USER->restricted_area($user);

	$data = $_DB->query(
		"SELECT * FROM accounts
			WHERE user_id = :user_id",
		[
			"user_id" => $user->id
		]
	);

	$modules = [
		VIEWS_DIR . "modules/dashboard/dashboard.php"
	];

	require_once VIEWS_DIR . "global/page.php";