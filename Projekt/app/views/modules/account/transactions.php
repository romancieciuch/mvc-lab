<article class="article page-width">
	<h1 class="article-title">
		Transakcje: <?php echo $data[0]["name"]; ?>
	</h1>

	<?php if (!empty($transactions)): ?>
		<div class="table-container">
			<table class="table table__mobile-friendly">
				<thead>
					<tr>
						<th>Nazwa</th>
						<th>Data</th>
						<th>Kategoria</th>
						<th>Kwota</th>
						<th>Akcje</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($transactions as $k=>$v): ?>
						<tr>
							<td class="table-name">
								<a href="/transaction/<?php echo $v["transaction_id"]; ?>/details/">
									<?php echo $v["name"]; ?>
								</a>
							</td>
							<td>
								<?php echo $v["transaction_date"]; ?>
							</td>
							<td>
								<a class="category" style="background: <?php echo $v["category_color"] ?? "#444"; ?>" href="/account/<?php echo $data[0]["id"]; ?>/transactions/category/<?php echo $v["category_id"] ?? 0; ?>/">
									<?php echo $v["category_name"] ?? "Bez kategorii"; ?>
								</a>
							</td>
							<td>
								<?php echo $_DB->nice_format($v["amount"]); ?> <?php echo $v["currency"]; ?>
							</td>
							<td class="table-options">
								<a href="/transaction/<?php echo $v["transaction_id"]; ?>/details/">Szczegóły</a>
								<a href="/transaction/<?php echo $v["transaction_id"]; ?>/edit/">Edytuj</a>
								<a class="accent" href="/transaction/<?php echo $v["transaction_id"]; ?>/delete/">Usuń</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="article-body page-width__narrow">
			<p>Nie masz jeszcze żadnych transakcji. Dodaj coś.</p>
		</div>
	<?php endif; ?>

	<p class="acenter">
		<a href="/transactions/0/create/" class="button">Nowa transakcja</a>
	</p>
</article>