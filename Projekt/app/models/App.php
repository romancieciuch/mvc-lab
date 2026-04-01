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

	public function pagination (int $page = 0, int $per_page = 0) : array {
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

	public function pagination_html (int $page = 0, int $per_page = 0, int $total = 0) : string {
		$html = '<nav class="pagination-container" aria-label="Nawigacja paginacji">';

		if ($page > 1)
			$html .= '<a href="?page='.($page - 1).'" class="page-link">Poprzednia</a>';

		if ($total > $page * $per_page)
			$html .= '<a href="?page='.($page + 1).'" class="page-link">Następna</a>';

		$html .= '</nav>';
		return $html;
	}

	public function fetch (string $method = "GET", string $url = "", string $auth_header = "", array $params = []) {
		$curl = curl_init();
		$method = strtoupper(trim($method));

		$headers = [
			"Authorization: " . $auth_header,
			"Content-Type: application/x-www-form-urlencoded"
		];

		$query_string = http_build_query($params);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query_string);

		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_SSL_VERIFYPEER => false,
        	CURLOPT_SSL_VERIFYHOST => 0
		]);

		$response = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$error = curl_error($curl);

		if ($error) {
			return [
				"success" => false,
				"code" => 500,
				"response" => $error
			];
		}

		return json_decode($response, true) ?? $response;
	}

	public function cache_is_valid (string $filepath, int $validity_in_seconds = 600) : bool {
		if (!file_exists($filepath)) return false;

		$file_modification_time = filemtime($filepath);
		$current_time = time();
		$file_age = $current_time - $file_modification_time;

		return $file_age <= $validity_in_seconds;
	}

	public function cache_save (string $filepath, string $content): bool {
		$directory = dirname($filepath);

		if (!is_dir($directory))
			if (!mkdir($directory, 0775, true))
				return false;

		return file_put_contents($filepath, $content) !== false;
	}

	public function get_currency_rates (string $file) {
		$is_valid = $this->cache_is_valid($file, 3600 * 12);

		if ($is_valid)
			return json_decode(file_get_contents($file), true);

		$currency_rates = $this->fetch("GET", "https://api.nbp.pl/api/exchangerates/tables/a/?format=json");
		$this->cache_save($file, json_encode($currency_rates[0], JSON_UNESCAPED_UNICODE));

		return $currency_rates;
	}
}