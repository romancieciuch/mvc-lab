<?php
	$_USER->restricted_area($user);

	$modules = [
		VIEWS_DIR . "modules/dashboard/dashboard.php"
	];

	require_once VIEWS_DIR . "global/page.php";