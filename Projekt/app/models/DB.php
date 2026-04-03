<?php

declare(strict_types=1);
namespace App\Models;

use PDO;
use PDOException;
use PDOStatement;

class DB {
	private PDO $pdo;

	public function __construct (array $config = []) {
		$dsn = sprintf(
			'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
			$config['host'] ?? "",
			$config['port'] ?? "",
			$config['name'] ?? ""
		);

		try {
			$this->pdo = new PDO($dsn, $config["user"] ?? "", $config["pass"] ?? "", [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false
			]);

		} catch (PDOException $e) {

			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
	}

	public function query (string $sql, array $params = []) : mixed {
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		preg_match('/^\s*([a-zA-Z]+)/', $sql, $matches);
    	$type = strtoupper($matches[1] ?? '');

		return match ($type) {
			"SELECT", "SHOW"	=> $stmt->fetchAll(),
			"INSERT"			=> $this->pdo->lastInsertId(),
			"UPDATE", "DELETE"	=> $stmt->rowCount(),
			default				=> true
		};
	}

	public function sanitize (string $string = "") : string {
		return trim(htmlspecialchars(strip_tags($string), ENT_QUOTES, "UTF-8"));
	}

	public function sanitize_array (array $arr = []) : array {
		$temp = [];

		foreach ($arr as $k=>$v)
			if (is_array($v))
				$temp[$k] = $this->sanitize_array($v);
			else
				$temp[$k] = $this->sanitize($v);

		return $temp;
	}

	public function truncate (string $string = "", int $max_length = 0) : string {
		if ($max_length <= 0) return $string;
		return mb_substr($string, 0, $max_length, "UTF-8");
	}

	public function generate_token (int $length = 32) : string {
		return bin2hex(random_bytes(intval($length / 2)));
	}

	public function email_validate (string $email = "") : bool {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function pagination (int|string $page = 1, int|string $per_page = 10) : string {
		$page = (int) $page;
		$per_page = (int) $per_page;

		$offset = ($page - 1) * $per_page;
		return " LIMIT {$per_page} OFFSET {$offset} ";
	}

	public function prepare_for_update (array $data, array $allowed_columns) : array {
		$sql = [];
		$sql_data["id"] = $data["id"];

		foreach ($data as $k=>$v)
			if (in_array($k, $allowed_columns)) {
				$sql[] = " {$k} = :{$k} ";
				$sql_data[$k] = $v;
			}

		$sql = implode(", ", $sql);

		return [
			"sql" => $sql,
			"sql_data" => $sql_data
		];
	}

	public function nice_format (float $amount = 0) : string {
    	return number_format($amount, 2, ',', ' ');
	}

	public function add_plus (float $amount = 0) : string {
    	return ($amount > 0) ? "+{$amount}" : $amount;
	}
}