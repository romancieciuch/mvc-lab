<?php

	// Czy formularz wysłany
	if (!empty($_POST["form-sent"])) {
		$grecaptcha = $_FORM->validate_recaptcha_v3($_POST["g-recaptcha-response"] ?? "");
	}

	// Czy Grecaptcha OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha)) {
		$dto = App\Models\DTO\AccountDTO::parse($_DB->sanitize_array($_POST));
		$errors = $dto->errors;
	}

	// Czy wszystko OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha) && empty($dto->errors)) {
		$accountdata = $account->create_account($user->id, $dto);
		$errors = $accountdata["errors"] ?? [];
	}

	// Wszystko OK
	if (!empty($accountdata) && empty($errors)) {
		header("Location: /dashboard/");
		exit;
	}