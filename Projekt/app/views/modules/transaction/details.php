<article class="article page-width__narrow">
	<h1 class="article-title">
		Szczegóły transakcji: <?php echo $data[0]["description"]; ?>
	</h1>

	<div class="table-container">
		<table class="table table__mobile-friendly">
			<tbody>
				<tr>
					<th>Konto</th>
					<td><?php echo $data[0]["account_name"]; ?></td>
				</tr>
				<tr>
					<th>Kategoria</th>
					<td>
						<span class="color-box" style="background-color: <?php echo $data[0]["color"]; ?>;"></span>
						<?php echo $data[0]["category_name"]; ?>
					</td>
				</tr>
				<tr>
					<th>Kwota</th>
					<td>
						<?php echo $data[0]["amount"]; ?> <?php echo $data[0]["currency"]; ?>
					</td>
				</tr>
				<tr>
					<th>Data transakcji</th>
					<td>
						<?php echo $data[0]["transaction_date"]; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<p class="acenter"><strong><a href="/dashboard/">Powrót</a></strong></p>
</article>