<?php

	// Strefa czasowa
	date_default_timezone_set("Europe/Warsaw");

	// Obsługa błędów
	if (ENV === "prod") {
		ini_set("display_errors", 0);
		ini_set("log_errors", 1);
		ini_set("error_log", RUNTIME_DIR . "error.log");
		error_reporting(E_ALL);
	} else {
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}

	// Autoloader klas
	spl_autoload_register (function ($class) {
		$prefix = "App\\";
		$len = strlen($prefix);

		if (strncmp($prefix, $class, $len) !== 0) return;

		$relative_class = substr($class, $len);
		$file = APP_DIR . str_replace("\\", "/", $relative_class) . ".php";

		if (file_exists($file))
			require_once $file;
	});