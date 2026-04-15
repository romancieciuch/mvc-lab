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
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>" required>
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="currency">Waluta rozliczeniowa</label>
			<select name="currency" id="currency" required>
				<option value="PLN">polski złoty (PLN)</option>
				<?php foreach ($_APP->currency_rates as $currency): ?>
					<option value="<?php echo $currency["code"]; ?>">
						<?php echo $currency["currency"]; ?> (<?php echo $currency["code"]; ?>)
					</option>
				<?php endforeach; ?>
			</select>
			<?php echo $_FORM->field_error($dto->errors["currency"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="priority">Priorytet</label>
			<input type="number" id="priority" name="priority" value="<?php echo intval($_POST["priority"] ?? 0); ?>" min="0" max="100" step="1" required>
			<?php echo $_FORM->field_error($dto->errors["priority"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="include_in_total">Uwzględnij w liczeniu statystyk całościowych</label>
			<select id="include_in_total" name="include_in_total">
				<option value="1"<?php if (!empty($_POST["include_in_total"])) echo ' selected'; ?>>Tak</option>
				<option value="0">Nie</option>
			</select>
			<?php echo $_FORM->field_error($dto->errors["include_in_total"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Twórz konto</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("account-form"); ?>
	</form>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>