<article class="article page-width__midi">
	<h1 class="article-title">
		Twoje dane
	</h1>

	<div class="tabs-container">
		<nav class="tabs-nav" aria-label="Nawigacja">
			<a href="/profile/" class="tab-item active" aria-current="page">Twoje dane</a>
			<a href="/profile/password/" class="tab-item">Zmiana hasła</a>
			<a href="/profile/2fa/" class="tab-item">Logowanie dwuetapowe</a>
			<a href="/profile/statistics/" class="tab-item">Statystyka</a>
		</nav>
	</div>

	<form class="form page-width__narrow" id="profile-form" method="POST">
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
			<label for="name">Nazwa użytkownika</label>
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user->name ?? ""); ?>" required>
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="email">Adres e-mail</label>
			<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->email ?? ""); ?>" disabled>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Aktualizuj</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("profile-form"); ?>
	</form>

	<p class="acenter">
		<strong><a class="accent" href="/delete/">Usuń konto</a></strong>
	</p>
</article>