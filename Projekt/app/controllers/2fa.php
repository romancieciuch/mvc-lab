<?php

	$data = [];
	$errors = [];

	// Czy formularz wysłany
	if (!empty($_POST["form-sent"])) {
		$grecaptcha = $_FORM->validate_recaptcha_v3($_POST["g-recaptcha-response"] ?? "");
	}

	// Czy Grecaptcha OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha)) {
		$ga = new App\Models\GoogleAuthenticator();
		$secret = $_USER->get_user_secret($user->id)[0]["two_factor_secret"] ?? "";
		$success_2fa = $ga->verify_code($secret, $_POST["code"] ?? "");

		if (!empty($success_2fa)) {
			$_USER->login_2fa();
			$user = App\Models\DTO\UserDTO::parse($_USER->me($user));
		} else
			$errors["code"] = "Błędny kod";
	}

	// Wszystko OK
	if (empty($errors) && !empty($success_2fa)) {
		header("Location: /dashboard/");
		exit;
	}

	// Wczytanie widoku
	$modules = [
		VIEWS_DIR . "modules/user/2fa.php"
	];

	require_once VIEWS_DIR . "global/page.php";