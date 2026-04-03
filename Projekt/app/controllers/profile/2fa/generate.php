<?php

	$ga = new App\Models\GoogleAuthenticator();
	$secret = $ga->generate_secret();

	$_USER->update_two_factor_secret($user->id, $secret);
	$user = App\Models\DTO\UserDTO::parse($_USER->me($user));

	$uri = $ga->get_provisioning_uri($user->email, $secret);

	$qrcode_svg = $ga->generate_qr_svg($uri);