<?php

	if ($user->two_factor_auth && $user->logged_in_2FA)
		require_once("dashboard.php");
	else if (!$user->two_factor_auth && $user->logged_in)
		require_once("dashboard.php");
	else
		require_once("login.php");