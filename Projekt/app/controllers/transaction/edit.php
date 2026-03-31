<?php

	$_USER->verify_user($user->id, $data[0]["account_id"] ?? 0, $transaction_id ?? 0);

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
		$transactiondata = $transaction->update_transaction($transaction_id, $dto);
		$errors = $userdata["errors"] ?? [];
	}

	// Wszystko OK
	if (!empty($transactiondata) && empty($errors)) {
		header("Location: /dashboard/");
		exit;
	}