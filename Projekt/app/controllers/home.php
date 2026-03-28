<?php

	if (!empty($_SESSION["USER"]))
		require_once("dashboard.php");
	else
		require_once("login.php");