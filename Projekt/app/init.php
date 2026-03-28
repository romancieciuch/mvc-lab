<?php

	define("ROOT_PATH",	$_SERVER["DOCUMENT_ROOT"] . "/");
	$config_file = ROOT_PATH . "../env/" . $_SERVER["SERVER_NAME"] . ".php";

	if (file_exists($config_file))
		$_CONFIG = require_once($config_file);
	else
		exit("No config file");

	define("APP_DIR",			ROOT_PATH . "../app/");
	define("CACHE_DIR",			ROOT_PATH . "../lib/");
	define("LIB_DIR",			ROOT_PATH . "../lib/");

	define("CONTROLLERS_DIR",	APP_DIR	  . "../controllers/");
	define("MODELS_DIR",		APP_DIR	  . "../models/");
	define("VIEWS_DIR",			APP_DIR	  . "../views/");

	define("DTO_DIR",			MODELS_DIR. "DTO/");

	define("ENV",				$_CONFIG["ENV"]);
	define("URL",				$_CONFIG["URL"]);

	var_dump($_CONFIG);