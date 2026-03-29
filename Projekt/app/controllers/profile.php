<?php
	$_USER->restricted_area($user);

	// Czy formularz wysłany
	if (!empty($_POST["form-sent"])) {
		$grecaptcha = $_FORM->validate_recaptcha_v3($_POST["g-recaptcha-response"] ?? "");
	}

	// Czy Grecaptcha OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha)) {
		$dto = App\Models\DTO\UpdateUserDTO::parse($_POST + ["id" => $user->id]);
		$errors = $dto->errors;
	}

	// Czy wszystko OK
	if (!empty($_POST["form-sent"]) && !empty($grecaptcha) && empty($dto->errors)) {
		$userdata = $_USER->update($dto);
		$errors = $userdata["errors"] ?? [];

		if (empty($errors)) {
			$user = App\Models\DTO\UserDTO::parse($_USER->me($user));
			$message["global"] = "Dane zostały zaktualizowane";
		}
	}

	$modules = [
		VIEWS_DIR . "modules/user/profile.php"
	];

	require_once VIEWS_DIR . "global/page.php";