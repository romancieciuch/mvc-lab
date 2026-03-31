<?php

declare(strict_types=1);
namespace App\Models;

class App {
	public function route (string $uri = "") : array {
		$arr = explode("/", trim($uri, "/"));

		return [
			"controller" => !empty($arr[0]) ? $arr[0] : "home",
			"params" => $arr
		];
	}

	public function pagination (int $page = 0, int $per_page = 0) {
		if (empty($page))
			$page = $_GET["page"] ?? 1;

		if (empty($per_page))
			$per_page = $_GET["per-page"] ?? 10;

		return [
			"page" => $page,
			"limit" => $per_page,
			"offset" => intval(($page - 1) * $limit)
		];
	}
}