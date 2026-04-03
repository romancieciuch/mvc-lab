<article class="article page-width__narrow">
	<h1 class="article-title">
		Logowanie dwuetapowe
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
			<label for="code">Kod z aplikacji uwierzytelniającej</label>
			<input type="text" id="code" name="code" value="<?php echo htmlspecialchars($_POST["code"] ?? ""); ?>" autocomplete="username">
			<?php echo $_FORM->field_error($errors["code"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Zaloguj</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("login-form"); ?>

		<div class="form-row">
			<small>Straciłeś kod dostęp do aplikacji uwierzytelniającej? <strong><a href="/contact/">Skontaktuj się z nami</a></strong></small>
		</div>
	</form>
</article>