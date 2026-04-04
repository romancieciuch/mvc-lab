<?php

	// Czy formularz wysłany
	if (!empty($_POST["form-sent"])) {
		$grecaptcha = $_FORM->validate_recaptcha_v3($_POST["g-recaptcha-response"] ?? "");
	}

	// Czy Grecaptcha OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha)) {
		$dto = App\Models\DTO\UserSettingsDTO::parse($_DB->sanitize_array($_POST));
		$errors = $dto->errors;
	}

	// Czy wszystko OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha) && empty($dto->errors)) {
		$_USER->update_user_settings($user->id, $dto);

		$message["global"] = "Dane zostały zaktualizowane";
	}

	// Opcje użytkownika
	$settings = $_USER->get_user_settings($user->id);