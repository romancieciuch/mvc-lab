<?php

	$view = VIEWS_DIR . "modules/static/" . $_ROUTING["controller"] . ".php";

	if (file_exists($view))
		$modules = [$view];
	else {
		http_response_code(404);
		$modules = [VIEWS_DIR . "modules/static/404.php"];
	}

	require_once VIEWS_DIR . "global/page.php";