<?php

	// Czy formularz wysłany
	if (!empty($_POST["form-sent"])) {
		$grecaptcha = $_FORM->validate_recaptcha_v3($_POST["g-recaptcha-response"] ?? "");
	}

	// Czy Grecaptcha OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha)) {
		$dto = App\Models\DTO\TransactionDTO::parse($_DB->sanitize_array($_POST));
		$errors = $dto->errors;
	}

	// Czy wszystko OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha) && empty($dto->errors)) {
		$transactiondata = $transaction->create_transaction($dto);
		$errors = $userdata["errors"] ?? [];
	}

	// Wszystko OK
	if (!empty($transactiondata) && empty($errors)) {
		$account_id = $_GET["account-id"] ?? 0;

		if ($account_id)
			header("Location: /account/{$account_id}/transactions/");
		else
			header("Location: /dashboard/");

		exit;
	}