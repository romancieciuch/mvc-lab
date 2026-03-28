<?php

	$data["users"] = $_DB->query("SELECT * FROM users");

	$modules = [
		VIEWS_DIR . "modules/users/users.php"
	];

	require_once VIEWS_DIR . "global/page.php";