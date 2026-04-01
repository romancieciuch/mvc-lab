<?php

	define("ROOT_PATH",	$_SERVER["DOCUMENT_ROOT"] . "/");

	// Dane konfiguracyjne
	$config_file = ROOT_PATH . "../env/" . $_SERVER["SERVER_NAME"] . ".php";
	if (file_exists($config_file))
		$_CONFIG = require_once($config_file);
	else
		exit("No config file");

	// Ścieżki
	define("APP_DIR",			ROOT_PATH . "../app/");
	define("CACHE_DIR",			ROOT_PATH . "../cache/");
	define("LIB_DIR",			ROOT_PATH . "../lib/");
	define("RUNTIME_DIR",		ROOT_PATH . "../runtime/");
	define("WWW_DIR",			ROOT_PATH);

	define("CONTROLLERS_DIR",	APP_DIR	  . "controllers/");
	define("MODELS_DIR",		APP_DIR	  . "models/");
	define("VIEWS_DIR",			APP_DIR	  . "views/");

	define("DTO_DIR",			MODELS_DIR. "DTO/");

	define("ENV",				$_CONFIG["ENV"]);
	define("URL",				$_CONFIG["URL"]);

	require_once("global.php");

	// Połączenie z bazą danych
	$_DB = new App\Models\DB([
		"host" => $_CONFIG["MYSQL_HOST"],
		"user" => $_CONFIG["MYSQL_USER"],
		"pass" => $_CONFIG["MYSQL_PASSWORD"],
		"port" => $_CONFIG["MYSQL_PORT"],
		"name" => $_CONFIG["MYSQL_DATABASE"]
	]);

	// Wysyłka maili
	$_MAIL = new App\Models\Mail([
		"host" => $_CONFIG["SMTP_HOST"],
		"port" => $_CONFIG["SMTP_PORT"],
		"from" => $_CONFIG["MAIL_FROM"],
		"auth" => $_CONFIG["SMTP_AUTH"]
	]);

	// Formularze
	$_FORM = new App\Models\Form($_CONFIG);

	// Użytkownicy
	$_USER = new App\Models\UserService($_DB, $_MAIL, $_CONFIG);

	// Użytkownik
	$user = App\Models\DTO\UserDTO::parse($_SESSION["USER"] ?? []);

	// Nasza aplikacja
	$_APP = new App\Models\App();
	$_ROUTING = $_APP->route($_SERVER["REQUEST_URI"]);
	$controller = CONTROLLERS_DIR . $_ROUTING["controller"] . ".php";

	// Kursy walut
	$currency_rates = $_APP->get_currency_rates(CACHE_DIR . "api/nbp.json");

	if (file_exists($controller))
		require_once $controller;
	else
		require_once CONTROLLERS_DIR . "page.php";