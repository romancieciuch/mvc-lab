<article class="article page-width__narrow">
	<h1 class="article-title">
		Rejestracja
	</h1>

	<form class="form" id="registration-form" method="POST">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z rejestracją",
					"desc" => $errors["global"]
				]);
		?>

		<div class="form-row">
			<label for="name">Nazwa użytkownika</label>
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="email">Adres e-mail</label>
			<input type="text" id="email" name="email" value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["email"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="password">Hasło</label>
			<input type="password" id="password" name="password">
			<?php echo $_FORM->field_error($dto->errors["password"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="password2">Powtórz hasło</label>
			<input type="password" id="password2" name="password2">
			<?php echo $_FORM->field_error($dto->errors["password2"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Zarejestruj</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("registration-form"); ?>
	</form>

	<p class="acenter">Masz już konto? <strong><a href="/login/">Zaloguj się</a></strong></p>
</article>