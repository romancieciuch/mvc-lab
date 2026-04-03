<?php

	$_USER->update_two_factor_secret($user->id, "");
	$user = App\Models\DTO\UserDTO::parse($_USER->me($user));