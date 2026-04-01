<article class="article page-width__narrow">
	<h1 class="article-title">
		<small class="article-title-breadcrumb">Szczegóły transakcji:</small>
		<?php echo $data[0]["name"]; ?>
	</h1>

	<div class="table-container">
		<table class="table">
			<tbody>
				<tr>
					<th>Konto</th>
					<td><?php echo $data[0]["account_name"]; ?></td>
				</tr>
				<tr>
					<th>Kategoria</th>
					<td>
						<span class="color-box" style="background-color: <?php echo $data[0]["color"] ?? "#444"; ?>;"></span>
						<?php echo $data[0]["category_name"] ?? "Bez kategorii"; ?>
					</td>
				</tr>
				<tr>
					<th>Kwota</th>
					<td>
						<span class="balance-amount"><?php echo $_DB->nice_format($data[0]["amount"]); ?></span>
						<span class="balance-currency"><?php echo $data[0]["currency"]; ?></span>
					</td>
				</tr>
				<tr>
					<th>Data transakcji</th>
					<td>
						<?php echo $data[0]["transaction_date"]; ?>
					</td>
				</tr>
				<tr>
					<th>Opis transakcji</th>
					<td>
						<?php echo $data[0]["description"]; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<p class="acenter"><button class="back-button" onclick="history.back()">Powrót</button></p>
</article>