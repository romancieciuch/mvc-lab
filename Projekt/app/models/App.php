<?php

declare(strict_types=1);
namespace App\Models;

class App {
	public array $currency_rates = [];
	public string $prev_page = "";
	public string $current_page = "";

	public function __construct () {
		$this->currency_rates = $this->get_currency_rates(CACHE_DIR . "api/nbp.json");
		$this->browse_history();
	}

	public function route (string $uri = "") : array {
		$arr = explode("/", trim($uri, "/"));

		return [
			"controller" => !empty($arr[0]) ? $arr[0] : "home",
			"params" => $arr
		];
	}

	public function browse_history () {

		if (empty($_SESSION["history"]["current"])) {
			$this->prev_page = $_SESSION["history"]["prev"] = "";
			$this->current_page = $_SESSION["history"]["current"] = $_SERVER["REQUEST_URI"];

		} else if ($_SERVER["REQUEST_URI"] !== $_SESSION["history"]["current"]) {
			$_SESSION["history"]["prev"] = $this->prev_page = $_SESSION["history"]["current"];
			$_SESSION["history"]["current"] = $this->current_page = $_SERVER["REQUEST_URI"];

		} else {

			$this->prev_page = $_SESSION["history"]["prev"];
			$this->current_page = $_SERVER["REQUEST_URI"];
		}

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

	public function pagination_html (int $page = 1, int $per_page = 10, int $total = 0, array $queryParams = []): string {
		$totalPages = (int) ceil($total / $per_page);

		if ($totalPages <= 1) return '';

		$buildUrl = function (int $targetPage) use ($queryParams): string {
			$queryParams['page'] = $targetPage;
			return '?' . http_build_query($queryParams);
		};

		$html = '<nav class="pagination-container" aria-label="Nawigacja paginacji">';


		if ($page > 1)
			$html .= '<a href="' . $buildUrl($page - 1) . '" class="page-link">Poprzednia</a>';
		else
			$html .= '<span class="page-link disabled" aria-disabled="true">Poprzednia</span>';


		$html .= '<span class="page-ellipsis">Strona ' . $page . ' z ' . $totalPages . '</span>';


		if ($page < $totalPages)
			$html .= '<a href="' . $buildUrl($page + 1) . '" class="page-link">Następna</a>';
		else
			$html .= '<span class="page-link disabled" aria-disabled="true">Następna</span>';


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
		$arr = $currency_rates[0]["rates"] ?? [];

		$this->cache_save($file, json_encode($arr, JSON_UNESCAPED_UNICODE));
		return $arr;
	}

	public function exchange (float $amount, string $from, string $to) : float {
		$from = strtoupper($from);
		$to = strtoupper($to);

		if ($from === $to) return round($amount, 2);

		$rates = ["PLN" => 1.0];
		foreach ($this->currency_rates as $rate)
			if (isset($rate['code']) && isset($rate['mid']))
				$rates[$rate['code']] = (float)$rate['mid'];


		if (!isset($rates[$from]) || !isset($rates[$to]))
			return 0;

		$amountInPLN = $amount * $rates[$from];
		$convertedAmount = $amountInPLN / $rates[$to];

		return round($convertedAmount, 2);
	}

	public function recalculate (array $accounts = []) {
		$temp = [];

		foreach ($accounts as $account) {
			$account["balance_pln"] = $this->exchange(floatval($account["balance"]), $account["currency"], "PLN");
			$account["avg_transaction_pln"] = $this->exchange(floatval($account["avg_transaction"]), $account["currency"], "PLN");

			$temp[] = $account;
		}

		return $temp;
	}

	public function recalculate_summary (array $summary = [], array $accounts = []) {
		$avg_amount = [];
		$total_balance = 0;

		foreach ($accounts as $account) {
			$avg_amount[] = $account["avg_transaction_pln"];
			$total_balance += $account["balance_pln"];
		}

		$summary["avg_amount"] = array_sum($avg_amount) / count($avg_amount);
		$summary["total_balance"] = $total_balance;

		return $summary;
	}

	public function predefined_colors () {
		return [
			'#666666', '#f9cb9c', '#ffe599', '#b6d7a8', '#9fc5e8', '#b4a7d6', '#d5a6bd', '#ea9999',
			'#444444', '#f6b26b', '#ffd966', '#93c47d', '#6fa8dc', '#8e7cc3', '#c27ba0', '#e06666',
			'#222222', '#e69138', '#f1c232', '#6aa84f', '#3d85c6', '#674ea7', '#a64d79', '#cc0000',
			'#000000', '#b45f06', '#bf9000', '#38761d', '#0b5394', '#351c75', '#741b47', '#990000'
		];
	}

	public function months_difference (string $date) : int {
		$from = new \DateTime($date);
		$to = new \DateTime();
		$diff = $from->diff($to);

		return ($diff->y * 12) + $diff->m;
	}

	function money_lasts (float $balance, float $monthlyUsage) : array {
		if ($monthlyUsage <= 0) $monthlyUsage = 1;
		$totalMonths = (int) floor($balance / $monthlyUsage);

		$years = intdiv($totalMonths, 12);
		$months = $totalMonths % 12;

		$summary = "";
		if ($years > 0) $summary .= "{$years} L ";
		$summary .= "{$months} M";

		return [
			'years' => $years,
			'months' => $months,
			'total_months' => $totalMonths,
			'summary' => $summary
		];
	}
}