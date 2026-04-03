<article class="article page-width__narrow">
	<h1 class="article-title">
		Odzyskiwanie hasła
	</h1>

	<form class="form" id="password-recovery-form" method="POST">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z logowaniem",
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
			<label for="email">Adres e-mail</label>
			<input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>" autocomplete="email" required>
			<?php echo $_FORM->field_error($dto->errors["email"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Odzyskaj hasło</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("password-recovery-form"); ?>
	</form>

	<p class="acenter">Przejdź do <strong><a href="/login/">logowania</a></strong></p>
</article>