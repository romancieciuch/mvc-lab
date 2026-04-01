<?php

	// Strefa czasowa
	date_default_timezone_set("Europe/Warsaw");

	// Obsługa błędów
	if (DEBUG === true) {
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	} else {
		ini_set("display_errors", 0);
		ini_set("log_errors", 1);
		ini_set("error_log", RUNTIME_DIR . "error.log");
		error_reporting(E_ALL);
	}

	// Autoloader klas
	spl_autoload_register(function ($class) {
	    $prefix = "App\\";
	    $len = strlen($prefix);

	    if (strncmp($prefix, $class, $len) !== 0) return;

	    $relative_class = substr($class, $len);
	    $parts = explode('\\', $relative_class);
	    $className = array_pop($parts);
	    $directories = strtolower(implode('/', $parts));
	    $dirPath = $directories ? $directories . '/' : '';
	    $file = APP_DIR . $dirPath . $className . ".php";

	    if (file_exists($file)) require_once $file;
	});