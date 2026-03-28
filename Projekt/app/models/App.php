<?php

declare(strict_types=1);
namespace App\Models;

class App {
	public function __construct () {
	}

	public function route (string $uri = "") {
		$arr = explode("/", trim($uri, "/"));

		return [
			"controller" => !empty($arr[0]) ? $arr[0] : "home",
			"params" => $arr
		];
	}
}