<article class="article page-width__narrow">
	<h1 class="article-title">
		Nowe konto
	</h1>

	<form class="form" id="account-form" method="POST">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z tworzeniem konta",
					"desc" => $errors["global"]
				]);
		?>

		<div class="form-row">
			<label for="name">Nazwa konta</label>
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="balance">Saldo początkowe</label>
			<input type="number" id="balance" name="balance" value="<?php echo htmlspecialchars($_POST["balance"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["balance"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="currency">Waluta rozliczeniowa</label>
			<select name="currency" id="currency">
				<option value="PLN">PLN</option>
				<option value="EUR">EUR</option>
				<option value="GBP">GBP</option>
				<option value="USD">USD</option>
			</select>
			<?php echo $_FORM->field_error($dto->errors["currency"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Twórz konto</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("account-form"); ?>
	</form>

	<p class="acenter"><strong><a href="/dashboard/">Powrót</a></strong></p>
</article>