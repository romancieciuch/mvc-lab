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
			$per_page = $_GET["per-page"] ?? 5;

		return [
			"page" => $page,
			"limit" => $per_page,
			"offset" => intval(($page - 1) * $per_page)
		];
	}

	public function pagination_html (int $page = 0, int $per_page = 0, int $total = 0) {
		$html = '<nav class="pagination-container" aria-label="Nawigacja paginacji">';

		if ($page > 1)
			$html .= '<a href="?page='.($page - 1).'" class="page-link">Poprzednia</a>';

		if ($total > $page * $per_page)
			$html .= '<a href="?page='.($page + 1).'" class="page-link">Następna</a>';

		$html .= '</nav>';
		return $html;
	}
}