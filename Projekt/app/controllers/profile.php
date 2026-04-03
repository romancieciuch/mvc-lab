<?php
	$_USER->restricted_area($user);

	$action = $_ROUTING["params"][1] ?? "details";
	$sub_action = $_ROUTING["params"][2] ?? "";


	if ($action === "details")
		require_once("profile/details.php");

	if ($action === "password")
		require_once("profile/password.php");

	if ($action === "2fa" && empty($sub_action))
		require_once("profile/2fa.php");

	if ($action === "2fa" && $sub_action === "generate") {
		$action = "2fa/generate";
		require_once("profile/2fa/generate.php");
	}

	if ($action === "2fa" && $sub_action === "deactivate") {
		$action = "2fa/deactivate";
		require_once("profile/2fa/deactivate.php");
	}


	$modules = [
		VIEWS_DIR . "modules/profile/{$action}.php"
	];

	require_once VIEWS_DIR . "global/page.php";