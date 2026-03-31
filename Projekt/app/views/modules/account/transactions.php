<article class="article page-width">
	<div class="article-title-wrap">
		<h1 class="article-title">
			Transakcje: <?php echo $data[0]["name"]; ?>
		</h1>

		<div class="article-title-actions">
			<a href="/transaction/0/create/" class="button">Nowa transakcja</a>
		</div>
	</div>

	<?php if (!empty($transactions["data"])): ?>
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
					<?php foreach ($transactions["data"] as $k=>$v): ?>
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

		<?php echo $_APP->pagination_html($pagination["page"], $pagination["limit"], $transactions["total"]); ?>

	<?php else: ?>
		<div class="article-body page-width__narrow">
			<p>Nie masz jeszcze żadnych transakcji. Dodaj coś.</p>
		</div>
	<?php endif; ?>

	<p class="acenter"><strong><a href="/dashboard/">Powrót</a></strong></p>
</article>