<article class="article page-width__narrow">
	<h1 class="article-title">
		Logowanie
	</h1>

	<form class="form" id="login-form" method="POST">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z logowaniem",
					"desc" => $errors["global"]
				]);
		?>

		<div class="form-row">
			<label for="email">Adres e-mail</label>
			<input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>" autocomplete="username">
			<?php echo $_FORM->field_error($dto->errors["email"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="password">Hasło</label>
			<input type="password" id="password" name="password" autocomplete="current-password">
			<?php echo $_FORM->field_error($dto->errors["password"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Zaloguj</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("login-form"); ?>

		<div class="form-row">
			<small>Zapomniałeś hasła? <strong><a href="/password-recovery/">Odzyskaj hasło</a></strong></small>
		</div>
	</form>

	<p class="acenter">Nie masz konta? <strong><a href="/registration/">Zarejestruj się</a></strong></p>
</article>