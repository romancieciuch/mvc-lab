<article class="article page-width">
	<h1 class="article-title">
		Witaj, <span class="primary"><?php echo $user->name; ?></span>!
	</h1>

	<div class="summary-grid">
		<?php $sumClass = ($summary["total_balance"] < 0) ? 'text-expense' : 'text-income'; ?>

		<div class="summary-card">
			<div class="summary-label">Ilość transakcji</div>
			<div class="summary-value <?php echo $sumClass; ?>">
				<?php echo $summary["total_transactions"]; ?>
			</div>
		</div>

		<div class="summary-card">
			<div class="summary-label">Średnia transakcja</div>
			<div class="summary-value <?php echo $sumClass; ?>">
				<?php echo $_DB->nice_format($summary["avg_amount"]); ?> PLN
			</div>
		</div>

		<div class="summary-card">
			<div class="summary-label">Łączna wartość</div>
			<div class="summary-value <?php echo $sumClass; ?>">
				<?php echo $_DB->nice_format($summary["total_balance"]); ?> PLN
			</div>
		</div>

		<?php if (!empty($settings["world_ends"])): ?>
			<div class="summary-card">
				<div class="summary-label">Koniec świata: <?php echo $settings["world_ends"]; ?></div>
				<div class="summary-value">
					<?php echo $_DB->nice_format($summary["total_balance"] / $_APP->months_difference($settings["world_ends"])); ?> PLN / M
				</div>
			</div>
		<?php endif; ?>

		<?php if (!empty($settings["monthly_expenses"])): ?>
			<div class="summary-card">
				<div class="summary-label">Przy zużyciu: <?php echo $settings["monthly_expenses"]; ?> PLN / M</div>
				<div class="summary-value">
					<?php echo $_APP->money_lasts($summary["total_balance"], $settings["monthly_expenses"])["summary"]; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php if (!empty($data)): ?>
		<div class="table-container">
			<table class="table table__mobile-friendly">
				<thead>
					<tr>
						<th>Konto</th>
						<th>Ostatnia transakcja</th>
						<th class="aright">Saldo</th>
						<th>Akcje</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data as $k=>$v): ?>
						<tr>
							<td class="table-name">
								<a href="/account/<?php echo $v["id"]; ?>/transactions/">
									<?php echo $v["name"]; ?>
								</a>
							</td>
							<td>
								<?php echo date("Y-m-d", strtotime($v["updated_at"])); ?>
							</td>
							<td class="cell-balance">
								<span class="balance-amount<?php if ($v["balance"] < 0) echo ' is-negative' ?>"><?php echo $_DB->nice_format($v["balance"]); ?></span>
								<span class="balance-currency"><?php echo $v["currency"]; ?></span>

								<?php if ($v["currency"] !== "PLN"): ?>
									<small class="balance-exchange">
										<?php
											echo $_DB->nice_format(
												$_APP->exchange($v["balance"], $v["currency"], "PLN")
											);
										?> PLN
									</small>
								<?php endif; ?>
							</td>
							<td class="table-options table-options-has-menu">
								<div class="table-options-menu">
									<a href="/account/<?php echo $v["id"]; ?>/edit/">Edytuj</a>
									<a href="/account/<?php echo $v["id"]; ?>/categories/">Kategorie</a>
									<a href="/account/<?php echo $v["id"]; ?>/details/">Szczegóły</a>
									<a class="accent" href="/account/<?php echo $v["id"]; ?>/delete/">Usuń</a>
								</div>
								<button class="table-options-button"></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="article-body page-width__narrow">
			<p>Nie masz jeszcze żadnych kont. Dodaj coś.</p>
		</div>
	<?php endif; ?>

	<p class="acenter">
		<a href="/account/0/create/" class="button">Nowe konto</a>
	</p>
</article>