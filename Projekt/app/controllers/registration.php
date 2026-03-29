<?php

	$data = [];
	$errors = [];

	// Czy formularz wysłany
	if (!empty($_POST["form-sent"])) {
		$grecaptcha = $_FORM->validate_recaptcha_v3($_POST["g-recaptcha-response"] ?? "");
	}

	// Czy Grecaptcha OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha)) {
		$dto = App\Models\DTO\RegisterUserDTO::parse($_POST);
		$errors = $dto->errors;
	}

	// Czy wszystko OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha) && empty($dto->errors)) {
		$userdata = $_USER->create($dto);
		$errors = $userdata["errors"] ?? [];
	}

	// Wszystko OK
	if (!empty($userdata) && empty($errors)) {
		header("Location: /thank-you/");
		exit;
	}

	// Wczytanie widoku
	$modules = [
		VIEWS_DIR . "modules/user/registration.php"
	];

	require_once VIEWS_DIR . "global/page.php";