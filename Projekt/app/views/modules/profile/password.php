<article class="article page-width__narrow">
	<h1 class="article-title">
		Zmiana hasła
	</h1>

	<div class="tabs-container">
		<nav class="tabs-nav" aria-label="Nawigacja">
			<a href="/profile/" class="tab-item">Twoje dane</a>
			<a href="/profile/password/" class="tab-item active" aria-current="page">Zmiana hasła</a>
			<a href="/profile/2fa/" class="tab-item">Logowanie dwuetapowe</a>
		</nav>
	</div>

	<form class="form" id="profile-form" method="POST">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z aktualizacją danych",
					"desc" => $errors["global"]
				]);
		?>

		<?php
			if (!empty($message["global"]))
				echo $_FORM->global_message([
					"title" => $message["global"],
					// "desc" => $message["global"]
				]);
		?>

		<div class="form-row">
			<label for="password">Nowe hasło</label>
			<input type="password" id="password" name="password" autocomplete="new-password" required>
			<?php echo $_FORM->field_error($dto->errors["password"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="password2">Powtórz nowe hasło</label>
			<input type="password" id="password2" name="password2" autocomplete="new-password" required>
			<?php echo $_FORM->field_error($dto->errors["password2"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Aktualizuj</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("profile-form"); ?>
	</form>

	<p class="acenter"><a class="back-button" href="<?php echo $_APP->prev_page; ?>">Powrót</a></p>
</article>