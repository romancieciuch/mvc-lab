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
			<label for="currency">Waluta rozliczeniowa</label>
			<select name="currency" id="currency">
				<option value="PLN">polski złoty (PLN)</option>
				<?php foreach ($currency_rates["rates"] as $currency): ?>
					<option value="<?php echo $currency["code"]; ?>">
						<?php echo $currency["currency"]; ?> (<?php echo $currency["code"]; ?>)
					</option>
				<?php endforeach; ?>
			</select>
			<?php echo $_FORM->field_error($dto->errors["currency"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="priority">Priorytet</label>
			<input type="number" id="priority" name="priority" value="<?php echo intval($_POST["priority"] ?? 0); ?>" min="0" max="100" step="1">
			<?php echo $_FORM->field_error($dto->errors["priority"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Twórz konto</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("account-form"); ?>
	</form>

	<p class="acenter"><button class="back-button" onclick="history.back()">Powrót</button></p>
</article>