<article class="article page-width__narrow">
	<h1 class="article-title">
		<small class="article-title-breadcrumb">Edycja kategorii:</small>
		<?php echo $data[0]["name"]; ?>
	</h1>

	<form class="form" id="account-form" method="POST">
		<?php
			if (!empty($errors["global"]))
				echo $_FORM->global_error([
					"title" => "Wystąpił problem z edycją kategorii",
					"desc" => $errors["global"]
				]);
		?>

		<div class="form-row">
			<label for="name">Nazwa kategorii</label>
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data[0]["name"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["name"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="color">Kolor</label>
			<input type="color" id="color" name="color" value="<?php echo htmlspecialchars($data[0]["color"] ?? ""); ?>">
			<?php echo $_FORM->field_error($dto->errors["color"] ?? ""); ?>
		</div>

		<div class="form-row">
			<label for="account_id">Przypisz do konta</label>
			<select name="account_id" id="account_id" data-account>
				<?php foreach ($accounts as $account): ?>
					<option value="<?php echo $account["id"]; ?>"<?php echo ($data[0]["account_id"] === $account["id"]) ? " selected" : ""; ?>>
						<?php echo $account["name"]; ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php echo $_FORM->field_error($dto->errors["account_id"] ?? ""); ?>
		</div>

		<input type="hidden" name="form-sent" value="1">

		<div class="form-row">
			<button class="button" type="submit">Zapisz zmiany</button>
		</div>

		<?php echo $_FORM->generate_recaptcha_v3("account-form"); ?>
	</form>

	<p class="acenter"><button class="back-button" onclick="history.back()">Powrót</button></p>
</article>