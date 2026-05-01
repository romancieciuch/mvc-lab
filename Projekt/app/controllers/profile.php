<?php
	$_USER->restricted_area($user);

	$action = $_ROUTING["params"][1] ?? "details";
	$sub_action = $_ROUTING["params"][2] ?? "";
	$sub_action2 = $_ROUTING["params"][3] ?? "";

	if ($action === "details")
		require_once("profile/details.php");

	if ($action === "password")
		require_once("profile/password.php");

	if ($action === "statistics")
		require_once("profile/statistics.php");

	if ($action === "sessions")
		require_once("profile/sessions.php");

	if ($action === "sessions" && $sub_action2 === "delete") {
		$action = "sessions/delete";
		require_once("profile/sessions/delete.php");
	}

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

	if (empty($prev_page))
		$prev_page = "/profile/";


	$modules = [
		VIEWS_DIR . "modules/profile/{$action}.php"
	];

	require_once VIEWS_DIR . "global/page.php";