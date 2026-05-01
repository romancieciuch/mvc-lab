<?php

	$session_id = $_ROUTING["params"][2] ?? 0;
	$verification = $_USER->is_it_my_session($user->id, "id", $session_id);

	if (empty($verification)) {
		$_USER->logout();
		header("Location: /");
		exit;
	}

	if (isset($_GET["delete"]))
		$deleted = $_USER->delete_refresh_token("id", $session_id);
	else
		$data = $_USER->get_user_session($user->id, $session_id);

	$prev_page = "/profile/sessions/";