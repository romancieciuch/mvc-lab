<?php
	$_USER->restricted_area($user);

	// Czy naprawdę usuwamy
	$delete = isset($_GET["delete"]);

	// Usuwamy
	if (!empty($delete)) {
		$dto = App\Models\DTO\DeleteUserDTO::parse(["id" => $user->id]);
		$_USER->delete($dto);
	}

	$modules = [
		VIEWS_DIR . "modules/user/delete.php"
	];

	require_once VIEWS_DIR . "global/page.php";