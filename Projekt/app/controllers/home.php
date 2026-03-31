<?php

	if (!empty($user->id))
		require_once("dashboard.php");
	else
		require_once("login.php");