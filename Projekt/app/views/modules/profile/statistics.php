<article class="article page-width__midi">
	<h1 class="article-title">
		Statystyka
	</h1>

	<div class="tabs-container">
		<nav class="tabs-nav" aria-label="Nawigacja">
			<a href="/profile/" class="tab-item">Twoje dane</a>
			<a href="/profile/password/" class="tab-item">Zmiana hasła</a>
			<a href="/profile/2fa/" class="tab-item">Logowanie dwuetapowe</a>
			<a href="/profile/statistics/" class="tab-item active" aria-current="page">Statystyka</a>
			<a href="/profile/sessions/" class="tab-item">Sesje</a>
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

		<?php
			echo $_FORM->global_info([
				"title" => "Wcześniejsza emerytura",
				"desc" => "Uzupełnij poniższe pole, aby sprawdzić, jakim miesięcznym budżetem byś dysponował, gdybyś przestał zarabiać i miał przeżyć ze zgromadzonych środków do określonego dnia."
			]);
		?>

		<div class="form-row">
			<label for="world_ends">Data końca świata</label>
			<input type="date" id="world_ends" name="world_ends" value="<?php echo htmlspecialchars($settings["world_ends"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["world_ends"] ?? ""); ?>
		</div>

		<hr>

		<?php
			echo $_FORM->global_info([
				"title" => "Poduszka finansowa",
				"desc" => "Uzupełnij poniższe pole, aby zobaczyć na ile lat i miesięcy starczy Ci środków."
			]);
		?>

		<div class="form-row">
			<label for="monthly_expenses">Miesięczne zapotrzebowanie</label>
			<input type="number" id="monthly_expenses" name="monthly_expenses" value="<?php echo htmlspecialchars($settings["monthly_expenses"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["monthly_expenses"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Aktualizuj</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("profile-form"); ?>
	</form>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>