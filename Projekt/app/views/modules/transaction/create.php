<article class="article page-width__narrow">
	<h1 class="article-title">
		Nowa transakcja
	</h1>

	<form class="form" id="account-form" method="POST" data-account-type="<?php echo $account_info[0]["account_type"] ?? "personal"; ?>">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z tworzeniem transakcji",
					"desc" => $errors["global"]
				]);
		?>

		<div class="form-row">
			<label for="name">Nazwa transakcji</label>
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>" required>
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="description">Opis transakcji</label>
			<textarea id="description" name="description"><?php echo $_POST["description"] ?? ""; ?></textarea>
			<?php echo $_FORM->field_error($dto->errors["description"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="amount">Kwota brutto</label>
			<input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($_POST["amount"] ?? ""); ?>" step="0.01" required>
			<?php echo $_FORM->field_error($dto->errors["amount"] ?? ""); ?>
		</div>

		<div class="form-row form-row__account-type-business">
			<label for="vat_rate">Stawka podatku VAT</label>
			<input type="number" id="vat_rate" name="vat_rate" value="<?php echo htmlspecialchars($_POST["vat_rate"] ?? ""); ?>" step="0.01">
			<?php echo $_FORM->field_error($dto->errors["vat_rate"] ?? ""); ?>
		</div>

		<div class="form-row form-row__account-type-business">
			<label for="income_tax_rate">Stawka podatku dochodowego</label>
			<input type="number" id="income_tax_rate" name="income_tax_rate" value="<?php echo htmlspecialchars($_POST["income_tax_rate"] ?? ""); ?>" step="0.01">
			<?php echo $_FORM->field_error($dto->errors["income_tax_rate"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="transaction_date">Data transakcji</label>
			<input type="date" id="transaction_date" name="transaction_date" value="<?php echo htmlspecialchars($_POST["transaction_date"] ?? date("Y-m-d")); ?>" required>
			<?php echo $_FORM->field_error($dto->errors["transaction_date"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="account_id">Przypisz do konta</label>
			<select name="account_id" id="account_id" data-account required>
				<?php foreach ($accounts as $account): ?>
					<option data-account-type="<?php echo $account["account_type"]; ?>" value="<?php echo $account["id"]; ?>"<?php echo (intval(($_GET["account-id"] ?? 0)) === $account["id"]) ? " selected" : ""; ?>>
						<?php echo $account["name"]; ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php echo $_FORM->field_error($dto->errors["account_id"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="category_id">Przypisz do kategorii</label>
			<select name="category_id" id="category_id" data-categories>
				<option value="NULL">Bez kategorii</option>
			</select>
			<?php echo $_FORM->field_error($dto->errors["category_id"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Utwórz transakcję</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("account-form"); ?>
	</form>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>

<script>
	{
		const form = document.querySelector("form");
		const categories = <?php echo json_encode($groupped_categories, JSON_UNESCAPED_UNICODE); ?>;
		const select_account = document.querySelector("[data-account]");
		const select_categories = document.querySelector("[data-categories]");

		select_account.addEventListener("change", () => { update_categories(); update_account_type(); });

		update_categories();


		function update_categories () {
			const account_id = select_account.value;

			let html = `<option value="">Bez kategorii</option>`;

			if (categories[account_id])
				for (let category of categories[account_id])
					html += `<option value="${category.id}">${category.name}</option>`;

			select_categories.innerHTML = html;
		}

		function update_account_type () {
			form.setAttribute(
				"data-account-type",
				select_account.options[select_account.options.selectedIndex].dataset.accountType
			);
		}

	}
</script>