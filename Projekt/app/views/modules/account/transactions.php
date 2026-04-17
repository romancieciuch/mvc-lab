<article class="article page-width">
	<div class="article-title-wrap">
		<h1 class="article-title">
			<small class="article-title-breadcrumb">Transakcje:</small>
			<?php echo $data[0]["name"]; ?>
		</h1>

		<div class="article-title-actions">
			<a href="/transaction/0/create/?account-id=<?php echo $data[0]["id"]; ?>" class="button">Nowa transakcja</a>
			<a href="/account/<?php echo $data[0]["id"]; ?>/categories/" class="button button-accent">Kategorie</a>
			<a href="/account/<?php echo $data[0]["id"]; ?>/details/" class="button button-black">Szczegóły</a>
		</div>
	</div>

	<div class="summary-grid">
		<?php $sumClass = ($transactions["total_amount"] < 0) ? 'text-expense' : 'text-income'; ?>

		<div class="summary-card">
			<div class="summary-label">Ilość transakcji</div>
			<div class="summary-value <?php echo $sumClass; ?>">
				<?php echo $transactions["total"]; ?>
			</div>
		</div>

		<div class="summary-card">
			<div class="summary-label">Średnia transakcja</div>
			<div class="summary-value <?php echo $sumClass; ?>">
				<?php echo $_DB->nice_format($transactions["avg_amount"]); ?> <?php echo $data[0]["currency"]; ?>
			</div>

			<?php if ($data[0]["currency"] !== "PLN"): ?>
				<small class="balance-exchange">
					<?php
						echo $_DB->nice_format(
							$_APP->exchange($transactions["avg_amount"], $data[0]["currency"], "PLN")
						);
					?> PLN
				</small>
			<?php endif; ?>
		</div>

		<div class="summary-card">
			<div class="summary-label">Łącznie w okresie</div>
			<div class="summary-value <?php echo $sumClass; ?>">
				<?php echo $_DB->nice_format($transactions["total_amount"]); ?> <?php echo $data[0]["currency"]; ?>
			</div>

			<?php if ($data[0]["currency"] !== "PLN"): ?>
				<small class="balance-exchange">
					<?php
						echo $_DB->nice_format($pln_value);
					?> PLN
				</small>
			<?php endif; ?>
		</div>

		<?php if (!empty($settings["world_ends"])): ?>
			<div class="summary-card">
				<div class="summary-label">Koniec świata: <?php echo $settings["world_ends"]; ?></div>
				<div class="summary-value">
					<?php echo $_DB->nice_format($pln_value / $_APP->months_difference($settings["world_ends"])); ?> PLN / M
				</div>
			</div>
		<?php endif; ?>

		<?php if (!empty($settings["monthly_expenses"])): ?>
			<div class="summary-card">
				<div class="summary-label">Przy zużyciu: <?php echo $settings["monthly_expenses"]; ?> PLN / M</div>
				<div class="summary-value">
					<?php echo $_APP->money_lasts($pln_value, $settings["monthly_expenses"])["summary"]; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php if ($data[0]["account_type"] === "business"): ?>
		<div class="summary-grid">
			<div class="summary-card summary-card__company">
				<div class="summary-label">Zarobki w okresie</div>
				<div class="summary-value text-income">
					<?php echo $_DB->nice_format($summary["income"] ?? 0); ?> <?php echo $data[0]["currency"]; ?>
				</div>
			</div>
			<div class="summary-card summary-card__company">
				<div class="summary-label">Wydatki w okresie</div>
				<div class="summary-value text-expense">
					<?php echo $_DB->nice_format($summary["expense"] ?? 0); ?> <?php echo $data[0]["currency"]; ?>
				</div>
			</div>

			<div class="summary-card summary-card__company">
				<div class="summary-label">Podatki w okresie</div>
				<div class="summary-value text-tax">
					<?php echo $_DB->nice_format($summary["tax"] ?? 0); ?> <?php echo $data[0]["currency"]; ?>
				</div>
			</div>

			<div class="summary-card summary-card__company">
				<div class="summary-label">Automatyczne podatki</div>
				<div class="summary-value summary-value__normal">
					VAT: <?php echo $_DB->nice_format($taxes["vat"] ?? 0); ?> <?php echo $data[0]["currency"]; ?>
					<br>PIT: <?php echo $_DB->nice_format($taxes["income_tax"] ?? 0); ?> <?php echo $data[0]["currency"]; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="filter-panel">
		<button type="button" class="filter-toggle-btn" id="filter-toggle">
			<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
			</svg>
			<span id="filter-toggle-text">Pokaż filtry</span>
		</button>

		<form class="filter-form" id="filter-form" method="GET" action="">
			<div class="filter-group filter-search">
				<label for="searchQuery">Wyszukaj</label>
				<input type="text" id="searchQuery" name="search" placeholder="Nazwa transakcji, kategoria..." value="<?php echo htmlspecialchars($_GET["search"] ?? ""); ?>">
			</div>

			<div class="filter-group">
				<label>Zakres dat</label>
				<div class="filter-row filter-row__date">
					<input type="date" name="date-from" aria-label="Data od" value="<?php echo htmlspecialchars($_GET["date-from"] ?? ""); ?>">
					<span class="filter-separator">-</span>
					<input type="date" name="date-to" aria-label="Data do" value="<?php echo htmlspecialchars($_GET["date-to"] ?? ""); ?>">
				</div>
			</div>

			<div class="filter-group">
				<label>Kwota</label>
				<div class="filter-row filter-row__amount">
					<input type="number" step="0.01" name="amount-min" placeholder="Od" aria-label="Kwota od" value="<?php echo htmlspecialchars($_GET["amount-min"] ?? ""); ?>">
					<span class="filter-separator">-</span>
					<input type="number" step="0.01" name="amount-max" placeholder="Do" aria-label="Kwota do" value="<?php echo htmlspecialchars($_GET["amount-max"] ?? ""); ?>">
				</div>
			</div>

			<div class="filter-actions">
				<button type="reset" class="btn-filter-secondary">Wyczyść</button>
				<button type="submit" class="btn-filter-primary">Szukaj</button>
			</div>

			<?php if (!empty($data["categories"])): ?>
				<div class="filter-group filter-categories">
					<label>Kategorie</label>
					<?php
						foreach ($data["categories"] as $category):
							$active = ($category["id"] === intval($_GET["category-id"] ?? 0)) ? " is-active" : "";
							if ($active)
								echo '<input type="hidden" name="category-id" value="'.$category["id"].'">';
					?>
						<a class="category<?php echo $active; ?> color-<?php echo $_APP->color_lightness($category["color"] ?? '#444'); ?>" href="<?php echo ($active ? "?" : "?category-id=".$category["id"]) ; ?>" style="background-color: <?php echo $category["color"] ?? '#444'; ?>">
							<?php echo $category["name"]; ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif;  ?>
		</form>
	</div>

	<?php echo $chart_html ?? ""; ?>

	<?php if (!empty($transactions["data"])): ?>
		<div class="table-container">
			<table class="table table__mobile-friendly">
				<thead>
					<tr>
						<th>Nazwa</th>
						<th>Kategoria</th>
						<th>Data</th>
						<th class="aright">Kwota</th>
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
								<a class="category color-<?php echo $_APP->color_lightness($v["category_color"] ?? "#444"); ?>" style="background: <?php echo $v["category_color"] ?? "#444"; ?>" href="?category-id=<?php echo $v["category_id"] ?? 0; ?>">
									<?php echo $v["category_name"] ?? "Bez kategorii"; ?>
								</a>
							</td>
							<td>
								<?php echo $v["transaction_date"]; ?>
							</td>
							<td class="cell-balance">
								<span class="balance-amount<?php if ($v["amount"] < 0) echo ' is-negative' ?>"><?php echo $_DB->nice_format($v["amount"]); ?></span>
								<span class="balance-currency"><?php echo $v["currency"]; ?></span>

								<?php if ($v["currency"] !== "PLN"): ?>
									<small class="balance-exchange">
										<?php
											echo $_DB->nice_format(
												$_APP->exchange($v["amount"], $v["currency"], "PLN")
											);
										?> PLN
									</small>
								<?php endif; ?>
							</td>
							<td class="table-options table-options-has-menu">
								<div class="table-options-menu">
									<a href="/transaction/<?php echo $v["transaction_id"]; ?>/details/">Szczegóły</a>
									<a href="/transaction/<?php echo $v["transaction_id"]; ?>/edit/">Edytuj</a>
									<a class="accent" href="/transaction/<?php echo $v["transaction_id"]; ?>/delete/?account-id=<?php echo $data[0]["id"]; ?>">Usuń</a>
								</div>
								<button class="table-options-button"></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<?php echo $_APP->pagination_html($pagination["page"], $pagination["limit"], $transactions["total"], $_GET); ?>

	<?php else: ?>
		<div class="page-width__narrow">
			<div class="article-body">
				<p>Brak transakcji spełniających kryteria.</p>
			</div>
		</div>
	<?php endif; ?>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>

<script>
	{
		document.addEventListener('DOMContentLoaded', () => {
			const toggleBtn = document.getElementById('filter-toggle');
			const toggleText = document.getElementById('filter-toggle-text');
			const filterForm = document.getElementById('filter-form');

			if (toggleBtn && filterForm) {
				toggleBtn.addEventListener('click', () => {
					filterForm.classList.toggle('is-open');

					if (filterForm.classList.contains('is-open')) {
						toggleText.textContent = 'Ukryj filtry';
					} else {
						toggleText.textContent = 'Pokaż filtry';
					}
				});
			}
		});
	}
</script>