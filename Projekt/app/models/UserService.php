<?php

declare(strict_types=1);
namespace App\Models;

use App\Models\DTO\UserDTO;
use App\Models\DTO\RegisterUserDTO;
use App\Models\DTO\LoginUserDTO;
use App\Models\DTO\UpdateUserDTO;
use App\Models\DTO\DeleteUserDTO;
use App\Models\DTO\ActivateUserDTO;
use App\Models\DTO\PasswordRecoveryDTO;
use App\Models\DTO\ChangePasswordDTO;
use App\Models\DTO\UserSettingsDTO;

class UserService {
	private DB $db;
	private Mail $mail;
	private array $config;

	public function __construct(DB $db, Mail $mail, array $config) {
		$this->db = $db;
		$this->mail = $mail;
		$this->config = $config;
	}

	public function create (RegisterUserDTO $dto) {
		$errors = [];

		if (!empty($dto->errors))
			$errors["global"] = "Dane użytkownika zawierają błędy.";

		// Czy użytkownik istnieje
		if ($this->user_exists($dto->email, "email"))
			$errors["global"] = "Użytkownik o takim adresie e-mail już istnieje. <strong><a href=\"/login/\">Spróbuj się zalogować</a></strong>.";

		if (!empty($errors))
			return [
				"success" => false,
				"data" => [],
				"errors" => $errors
			];

		// Rejestrujemy
		$token = $this->db->generate_token(32);
		$res = $this->db->query(
			"INSERT INTO users (name, email, password_hash, token, active)
				VALUES (:name, :email, :password_hash, :token, :active)",
			[
				"name" => $dto->name,
				"email" => $dto->email,
				"password_hash" => password_hash($dto->password, PASSWORD_DEFAULT),
				"token" => $token,
				"active" => 0
			]
		);

		if (empty($res))
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Problem z tworzeniem użytkownika"]
			];

		// Wysyłamy maila aktywacyjnego
		$id = (int) $res[0];
		$link = $this->config["URL"] . "/activation/{$id}/{$token}/";
		$res = $this->mail->send(
			$dto->email,
			"Witamy w serwisie FLOW",
			"
				<h1>Witaj {$dto->name}!</h1>
				<p>
					Kliknij w link poniżej, aby aktywować konto:
					<br><a href=\"{$link}\">{$link}</a>
				</p>
			"
		);

		return [
			"success" => true,
			"data" => $res,
			"errors" => $errors
		];
	}

	public function activate (ActivateUserDTO $dto) {
		$errors = [];

		if (!empty($dto->errors))
			$errors["global"] = "Dane użytkownika zawierają błędy.";

		// Czy użytkownik istnieje
		if (!$this->user_exists($dto->id, "id"))
			$errors["global"] = "Użytkownik nie istnieje.";

		if (!empty($errors))
			return [
				"success" => false,
				"data" => [],
				"errors" => $errors
			];

		// Próba aktywacji
		$res = $this->db->query(
			"UPDATE users
				SET active = 1, token = NULL
					WHERE id = :id AND token = :token AND active = :active
						LIMIT 1",
			[
				"id" => $dto->id,
				"token" => $dto->token,
				"active" => 0
			]
		);

		if (empty($res))
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Konto aktywowane lub aktywacja nie powiodła się."]
			];

		return [
			"success" => true,
			"data" => $res,
			"errors" => $errors
		];
	}

	public function update (UpdateUserDTO $dto) {
		$errors = [];

		if (!empty($dto->errors))
			$errors["global"] = "Dane użytkownika zawierają błędy.";

		// Czy użytkownik istnieje
		if (!$this->user_exists($dto->id, "id"))
			$errors["global"] = "Użytkownik nie istnieje.";

		$res = $this->db->query(
			"UPDATE users
				SET name = :name
					WHERE id = :id
						LIMIT 1",
			[
				"name" => $dto->name,
				"id" => $dto->id
			]
		);

		if ($res === 0)
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Nic się nie zmieniło."]
			];

		if ($res === false)
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Problem z aktualizacją danych."]
			];

		return [
			"success" => true,
			"data" => $res,
			"errors" => $errors
		];
	}

	public function change_password (ChangePasswordDTO $dto) {
		$errors = [];

		if (!empty($dto->errors))
			$errors["global"] = "Dane użytkownika zawierają błędy.";

		// Czy użytkownik istnieje
		if (!$this->user_exists($dto->id, "id"))
			$errors["global"] = "Użytkownik nie istnieje.";

		// Jeśli zmiana hasła
		if (!empty($dto->password))
			$res = $this->db->query(
				"UPDATE users
					SET password_hash = :password
						WHERE id = :id
							LIMIT 1",
				[
					"password" => password_hash($dto->password, PASSWORD_DEFAULT),
					"id" => $dto->id
				]
			);

		if ($res === 0)
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Nic się nie zmieniło."]
			];

		if ($res === false)
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Problem z aktualizacją danych."]
			];

		return [
			"success" => true,
			"data" => $res,
			"errors" => $errors
		];
	}

	public function password_recovery (PasswordRecoveryDTO $dto) {
		$errors = [];

		if (!empty($dto->errors))
			$errors["global"] = "Dane użytkownika zawierają błędy.";

		// Czy użytkownik istnieje
		$userdata = $this->user_exists($dto->email, "email");

		if (empty($userdata[0]["id"]))
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Problem z resetowaniem hasła."]
			];

		// Generujemy token do zmiany hasła
		$token = $this->db->generate_token(32);
		$res = $this->db->query(
			"UPDATE users
				SET token = :token
					WHERE id = :id
						LIMIT 1",
			[
				"token" => $token,
				"id" => $userdata[0]["id"]
			]
		);

		if (empty($res))
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Problem z resetowaniem hasła."]
			];

		// Wysyłamy maila z hasłem jednorazowym
		$id = (int) $userdata[0]["id"];
		$link = $this->config["URL"] . "/new-password/{$id}/{$token}/";
		$res = $this->mail->send(
			$dto->email,
			"Odzyskiwanie hasła w serwisie FLOW",
			"
				<h1>Twoje jednorazowe hasło</h1>
				<p>
					To Twoje jednorazowe hasło do zalogowania w serwisie:
					<br><strong>{$token}</strong>
				</p>
				<p>
					Wykorzystaj je, aby zmienić hasło na nowe.
				</p>
				<p>
					Jeśli to nie Ty zgłosiłeś zmianę hasła - nic się nie martw - Twoje dane są bezpieczne.
				</p>
			"
		);

		return [
			"success" => true,
			"data" => $res,
			"errors" => $errors
		];
	}

	public function update_two_factor_secret (int $user_id, string $secret) {
		return $this->db->query(
			"UPDATE users SET two_factor_secret = :secret
				WHERE id = :user_id
					LIMIT 1",
			[
				"secret" => $secret,
				"user_id" => $user_id
			]
		);
	}

	public function get_user_secret (int $user_id) {
		return $this->db->query(
			"SELECT two_factor_secret
				FROM users
					WHERE id = :user_id
						LIMIT 1",
			[
				"user_id" => $user_id
			]
		);
	}

	public function delete (DeleteUserDTO $dto) {
		// Usuwamy
		$res = $this->db->query(
			"DELETE FROM users WHERE id = :id LIMIT 1",
			["id" => $dto->id]
		);

		if (empty($res))
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Użytkownik nie istnieje"]
			];

		// Wylogowujemy
		$this->logout();

		return [
			"success" => true,
			"data" => $res,
			"errors" => []
		];
	}

	public function me (UserDTO $dto) {
		$res = $this->db->query(
			"SELECT * FROM users WHERE id = :id LIMIT 1",
			["id" => $dto->id]
		);

		if (empty($res))
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Błąd pobierania danych o użytkowniku"]
			];

		$_SESSION["USER"] = [
			"logged_in" => $_SESSION["USER"]["logged_in"],
			"logged_in_2FA" => $_SESSION["USER"]["logged_in_2FA"],
			"id" => $res[0]["id"],
			"name" => $res[0]["name"],
			"email" => $res[0]["email"],
			"two_factor_auth" => (bool) $res[0]["two_factor_secret"],
			"created_at" => $res[0]["created_at"]
		];

		return $_SESSION["USER"];
	}

	public function login (LoginUserDTO $dto) {
		$errors = [];

		if (!empty($dto->errors))
			$errors["global"] = "Dane użytkownika zawierają błędy.";

		// Czy użytkownik istnieje
		if (!$this->user_exists($dto->email, "email"))
			$errors["global"] = "Użytkownik nie istnieje.";

		$res = $this->db->query(
			"SELECT * FROM users WHERE email = :email LIMIT 1",
			["email" => $dto->email]
		);

		// Czy użytkownik istnieje i hasło prawidłowe
		if (empty($res[0]["password_hash"]) || !password_verify($dto->password, $res[0]["password_hash"]))
			$errors["password"] = "Błędny adres e-mail i/lub hasło";

		// Czy to logowanie z hasłem jednorazowym
		if (!empty($res[0]["token"]) && $res[0]["token"] === $dto->password) {
			$this->db->query(
				"UPDATE users SET token = NULL WHERE email = :email LIMIT 1",
				["email" => $dto->email]
			);

			unset($errors["password"]);
		}

		if (!empty($errors))
			return [
				"success" => false,
				"data" => [],
				"errors" => ["global" => "Błędny adres e-mail i/lub hasło"]
			];


		$_SESSION["USER"] = [
			"logged_in" => true,
			"logged_in_2FA" => false,
			"id" => $res[0]["id"],
			"name" => $res[0]["name"],
			"email" => $res[0]["email"],
			"two_factor_auth" => (bool) $res[0]["two_factor_secret"],
			"created_at" => $res[0]["created_at"]
		];

		return [
			"success" => false,
			"data" => $_SESSION["USER"],
			"errors" => $errors
		];
	}

	public function login_2fa () {
		$_SESSION["USER"]["logged_in_2FA"] = true;
	}

	public function get_user_settings (int $user_id) {
		$res = $this->db->query(
			"SELECT settings FROM users WHERE id = :user_id LIMIT 1",
			["user_id" => $user_id]
		);

		return json_decode($res[0]["settings"] ?? "", true) ?? [];
	}

	public function update_user_settings (int $user_id, UserSettingsDTO $dto) {
		$settings = array_merge($this->get_user_settings($user_id), $dto->fields);

		return $this->db->query(
			"UPDATE users
				SET settings = :settings
					WHERE id = :user_id
						LIMIT 1",
			[
				"user_id" => $user_id,
				"settings" => json_encode($settings, JSON_UNESCAPED_UNICODE)
			]
		);
	}

	public function logout () {
		unset($_SESSION["USER"]);
	}

	public function restricted_area (UserDTO $dto) {
		if (!empty($dto->two_factor_auth) && empty($dto->logged_in_2FA)) {
			http_response_code(401);
			header("Location: /");
			exit;
		}

		if (empty($dto->logged_in)) {
			http_response_code(401);
			header("Location: /");
			exit;
		}
	}

	public function user_exists (string|int $value = "", string $field = "email") {
		if (empty($value)) return false;

		if ($field === "email")
			$res = $this->db->query("SELECT id FROM users WHERE email = :email LIMIT 1",
				["email" => $value]
			);

		if ($field === "id")
			$res = $this->db->query("SELECT id FROM users WHERE id = :id LIMIT 1",
				["id" => $value]
			);

		if (!empty($res)) return $res;
		return 0;
	}

	public function verify_user (int $user_id = 0, int $account_id = 0, int $transaction_id = 0, int $category_id = 0) {
		$res = $this->db->query(
			"SELECT id FROM accounts WHERE id = :id AND user_id = :user_id LIMIT 1",
			[
				"id" => $account_id,
				"user_id" => $user_id
			]
		);

		if (!empty($res) && !empty($transaction_id))
			$res = $this->db->query(
				"SELECT id FROM transactions WHERE id = :id AND account_id = :account_id LIMIT 1",
				[
					"id" => $transaction_id,
					"account_id" => $account_id
				]
			);

		if (!empty($category_id))
			$res = $this->db->query(
				"SELECT id FROM categories c
					WHERE c.id = :id
						AND account_id IN (SELECT id FROM accounts WHERE user_id = :user_id)
						LIMIT 1",
				[
					"id" => $category_id,
					"user_id" => $user_id
				]
			);

		if (!empty($res)) return true;

		// Do testów
		// return false;

		$this->logout();
		exit("Próba oszustwa!");
	}

	public function get_user_first_account_id (int $user_id = 0) {
		$res = $this->db->query(
			"SELECT a.id
				FROM accounts a
					WHERE a.user_id = :user_id
						ORDER BY a.id ASC
							LIMIT 1",
			[
				"user_id" => $user_id
			]
		);

		if (empty($res[0]["id"])) return 0;
		return $res[0]["id"];
	}
}