<?php

declare(strict_types=1);
namespace App\Models;

class Form {
	private array $config;

	public function __construct (array $config = []) {
		$this->config = $config;
	}

	public function global_error (array $args = []) : string {
		$title = !empty($args["title"]) ? '<span class="alert-error-title">' . $args["title"] . '</span>' : '';
		$desc = !empty($args["desc"]) ? '<p class="alert-error-text">' . $args["desc"] . '</p>' : '';

		if (empty($title) && empty($desc)) return '';

		return '
			<div class="alert-error" role="alert">
				<svg class="alert-error-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
					<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
				</svg>
				<div class="alert-error-content">
					' . $title . '
					' . $desc . '
				</div>
			</div>
		';
	}

	public function global_message (array $args = []) : string {
		$title = !empty($args["title"]) ? '<span class="alert-success-title">' . $args["title"] . '</span>' : '';
		$desc = !empty($args["desc"]) ? '<p class="alert-success-text">' . $args["desc"] . '</p>' : '';

		if (empty($title) && empty($desc)) return '';

		return '
			<div class="alert-success" role="alert">
				<svg class="alert-success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
					<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
				</svg>
				<div class="alert-success-content">
					' . $title . '
					' . $desc . '
				</div>
			</div>
		';
	}

	public function field_error (string $error = "") : string {
		if (empty($error)) return '';

		return '
			<span class="field-error-text">' . $error . '</span>
		';
	}

	function generate_recaptcha_v3 (string $id = "form", bool $send = true) : string {
		if (empty($this->config["GOOGLE_RECAPTCHA_V3_KEY"]))
			return "Brak klucza Google reCAPTCHA";

		return '
			<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-' . $id . '">
			<script>
				{
					if (!window._grecaptcha_loaded) {
						window._grecaptcha_loaded = true;

						let g = document.createElement("script");
						g.src = "https://www.google.com/recaptcha/api.js?render='.$this->config["GOOGLE_RECAPTCHA_V3_KEY"].'";
						g.async = true;
						document.head.appendChild(g);
					}

					document.getElementById("'.$id.'").addEventListener("submit", function(e) {
						e.preventDefault();

						grecaptcha.ready(function() {
							grecaptcha.execute("'.$this->config["GOOGLE_RECAPTCHA_V3_KEY"].'", {action: "submit"}).then(function(token) {
								document.getElementById("g-recaptcha-response-'.$id.'").value = token;
								'.(!empty($send) ? 'e.target.submit();' : '').'
							});
						});
					});
				}
			</script>
			<style>
				.grecaptcha-badge { visibility: hidden; }
			</style>
		';
	}

	function validate_recaptcha_v3 (string $response = "") : bool {
		if (empty($response)) return false;
		if (empty($this->config["GOOGLE_RECAPTCHA_V3_SECRET"])) return false;

		$recaptcha = json_decode(
			file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->config["GOOGLE_RECAPTCHA_V3_SECRET"] . "&response=" . $response),
			true
		);

		// weryfikacja pozytywna
		if ($recaptcha["success"] && $recaptcha["score"] >= 0.5) return true;

		return false;
	}
}