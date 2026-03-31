<?php
	$_USER->restricted_area($user);

	$account = new App\Models\Account($_DB);
	$data = $account->get_accounts($user->id);

	$modules = [
		VIEWS_DIR . "modules/dashboard/dashboard.php"
	];

	require_once VIEWS_DIR . "global/page.php";