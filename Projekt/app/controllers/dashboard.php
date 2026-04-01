<?php
	$_USER->restricted_area($user);

	$account = new App\Models\Account($_DB);
	$data = $account->get_accounts($user->id);
	$summary = $account->get_accounts_summary($user->id);

	// Dla kont o różnych walutach - potrzebna rekalkulacja
	if ($account->has_different_currencies($data)) {
		$data = $_APP->recalculate($data);
		$summary = $_APP->recalculate_summary($summary, $data);
	}

	$modules = [
		VIEWS_DIR . "modules/dashboard/dashboard.php"
	];

	require_once VIEWS_DIR . "global/page.php";