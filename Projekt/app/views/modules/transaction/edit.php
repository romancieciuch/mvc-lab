<article class="article page-width__narrow">
	<h1 class="article-title">
		<small class="article-title-breadcrumb">Edycja transakcji:</small>
		<?php echo $data[0]["name"]; ?>
	</h1>

	<form class="form" id="account-form" method="POST">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z tworzeniem transakcji",
					"desc" => $errors["global"]
				]);
		?>

		<div class="form-row">
			<label for="name">Nazwa transakcji</label>
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data[0]["name"] ?? ""); ?>" required>
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="description">Opis transakcji</label>
			<textarea id="description" name="description"><?php echo $data[0]["description"] ?? ""; ?></textarea>
			<?php echo $_FORM->field_error($dto->errors["description"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="amount">Kwota</label>
			<input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($data[0]["amount"] ?? ""); ?>" step="0.01" required>
			<?php echo $_FORM->field_error($dto->errors["amount"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="transaction_date">Data transakcji</label>
			<input type="date" id="transaction_date" name="transaction_date" value="<?php echo htmlspecialchars($data[0]["transaction_date"] ?? date("Y-m-d")); ?>" required>
			<?php echo $_FORM->field_error($dto->errors["transaction_date"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="account_id">Przypisz do konta</label>
			<select name="account_id" id="account_id" data-account required>
				<?php
					foreach ($accounts as $account):
						$selected = ($account["id"] === $data[0]["account_id"]) ? true : false;
				?>
					<option value="<?php echo $account["id"]; ?>"<?php echo $selected ? " selected" : ""; ?>>
						<?php echo $account["name"]; ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php echo $_FORM->field_error($dto->errors["account_id"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="category_id">Przypisz do kategorii</label>
			<select name="category_id" id="category_id" data-categories required>
				<option value="NULL">Bez kategorii</option>
			</select>
			<?php echo $_FORM->field_error($dto->errors["category_id"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Zapisz transakcję</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("account-form"); ?>
	</form>

	<p class="acenter"><button class="back-button" onclick="history.back()">Powrót</button></p>
</article>

<script>
	{
		const categories = <?php echo json_encode($groupped_categories, JSON_UNESCAPED_UNICODE); ?>;
		const select_account = document.querySelector("[data-account]");
		const select_categories = document.querySelector("[data-categories]");
		const selected_category = <?php echo $data[0]["category_id"] ?? "null"; ?>;

		select_account.addEventListener("change", update_categories);

		function update_categories () {
			const account_id = select_account.value;

			let html = `<option value="">Bez kategorii</option>`;

			if (categories[account_id])
				for (let category of categories[account_id])
					if (category.id === selected_category)
						html += `<option value="${category.id}" selected>${category.name}</option>`;
					else
						html += `<option value="${category.id}">${category.name}</option>`;

			select_categories.innerHTML = html;
		}

		update_categories();
	}
</script>