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
		$categorydata = $category->update_category($category_id, $dto);
		$errors = $categorydata["errors"] ?? [];
	}

	// Wszystko OK
	if (!empty($categorydata) && empty($errors)) {
		$account_id = $_GET["account-id"] ?? 0;

		if ($account_id)
			header("Location: /account/{$account_id}/categories/");
		else
			header("Location: /dashboard/");

		exit;
	}