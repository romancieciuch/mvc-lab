<article class="article page-width">
	<h1 class="article-title">
		<small class="article-title-breadcrumb">Edycja konta:</small>
		<?php echo $data[0]["name"]; ?>
	</h1>

	<div class="page-width__narrow">
		<form class="form" id="account-form" method="POST">
			<?php
				if (!empty($errors["global"]))
					echo $_FORM->global_error([
						"title" => "Wystąpił problem z edycją konta",
						"desc" => $errors["global"]
					]);
			?>

			<div class="form-row">
				<label for="name">Nazwa konta</label>
				<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data[0]["name"]); ?>" required>
				<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
			</div>

			<div class="form-row">
				<label for="currency">Waluta rozliczeniowa</label>
				<select name="currency" id="currency" required>
					<option value="PLN"<?php if ($data[0]["currency"] === "PLN") echo ' selected'; ?>>polski złoty (PLN)</option>

					<?php foreach ($_APP->currency_rates as $currency): ?>
						<option value="<?php echo $currency["code"]; ?>"<?php if ($data[0]["currency"] === $currency["code"]) echo ' selected'; ?>>
							<?php echo $currency["currency"]; ?> (<?php echo $currency["code"]; ?>)
						</option>
					<?php endforeach; ?>
				</select>
				<?php echo $_FORM->field_error($dto->errors["currency"] ?? ""); ?>
			</div>

			<div class="form-row">
				<label for="priority">Priorytet</label>
				<input type="number" id="priority" name="priority" value="<?php echo intval($data[0]["priority"] ?? 0); ?>" min="0" max="100" step="1" required>
				<?php echo $_FORM->field_error($dto->errors["priority"] ?? ""); ?>
			</div>

			<div class="form-row">
				<label for="include_in_total">Uwzględnij w liczeniu statystyk całościowych</label>
				<select id="include_in_total" name="include_in_total">
					<option value="1"<?php if (!empty($data[0]["include_in_total"]) && $data[0]["include_in_total"] === 1) echo ' selected'; ?>>Tak</option>
					<option value="0"<?php if (isset($data[0]["include_in_total"]) && $data[0]["include_in_total"] === 0) echo ' selected'; ?>>Nie</option>
				</select>
				<?php echo $_FORM->field_error($dto->errors["include_in_total"] ?? ""); ?>
			</div>

			<div class="form-row">
				<label for="account_type">Konto firmowe</label>
				<select id="account_type" name="account_type">
					<option value="personal"<?php if ("personal" === $data[0]["account_type"]) echo ' selected'; ?>>Osobiste</option>
					<option value="business"<?php if ("business" === $data[0]["account_type"]) echo ' selected'; ?>>Firmowe</option>
				</select>
				<?php echo $_FORM->field_error($dto->errors["account_type"] ?? ""); ?>
			</div>

			<input type="hidden" name="form-sent" value="1">

			<div class="form-row">
				<button class="button" type="submit">Zapisz zmiany</button>
			</div>

			<?php echo $_FORM->generate_recaptcha_v3("account-form"); ?>
		</form>
	</div>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>