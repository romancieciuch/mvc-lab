<?php

	// Czy formularz wysłany
	if (!empty($_POST["form-sent"])) {
		$grecaptcha = $_FORM->validate_recaptcha_v3($_POST["g-recaptcha-response"] ?? "");
	}

	// Czy Grecaptcha OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha)) {
		$dto = App\Models\DTO\CategoryDTO::parse($_DB->sanitize_array($_POST));
		$errors = $dto->errors;
	}

	// Czy wszystko OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha) && empty($dto->errors)) {
		$categorydata = $category->create_category($dto);
		$errors = $userdata["errors"] ?? [];
	}

	// Wszystko OK
	if (!empty($categorydata) && empty($errors)) {
		header("Location: /dashboard/");
		exit;
	}