<div class="page-width page-width__narrow">
	<h1 class="page-title acenter">
		Twoje dane
	</h1>

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
			<label for="name">Imię</label>
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user->name ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="password">Nowe hasło</label>
			<input type="password" id="password" name="password">
			<?php echo $_FORM->field_error($dto->errors["password"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="password2">Powtórz nowe hasło</label>
			<input type="password" id="password2" name="password2">
			<?php echo $_FORM->field_error($dto->errors["password2"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Aktualizuj</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("profile-form"); ?>
	</form>
</div>