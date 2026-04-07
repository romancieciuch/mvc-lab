<article class="article">
	<div class="page-width__narrow">
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
							<span class="balance-amount<?php if ($data[0]["amount"] < 0) echo ' is-negative' ?>"><?php echo $_DB->nice_format($data[0]["amount"]); ?></span>
							<span class="balance-currency"><?php echo $data[0]["currency"]; ?></span>
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

		<p class="acenter">
			<a class="button" href="/transaction/<?php echo $transaction_id; ?>/edit/">Edytuj</a>
		</p>

		<?php if (!empty($data[0]["description"])): ?>
			<div class="article-body">
				<h2>Opis transakcji</h2>
				<p><?php echo nl2br($data[0]["description"]); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<?php if (!empty($chart_html)): ?>
		<div class="page-width">
			<h2>Przebieg zmienności</h2>
			<?php echo $chart_html; ?>
		</div>
	<?php endif; ?>

		<div class="page-width__narrow">
		<?php if (!empty($history)): ?>
			<h2>Zapis zmian</h2>
			<div class="table-container">
				<table class="table">
					<thead>
						<tr>
							<th>Data</th>
							<th>Zmiana</th>
							<th>Kwota</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($history as $transaction): ?>
							<tr>
								<td>
									<?php echo $transaction["log_date"]; ?>
								</td>
								<td>
									<span class="<?php echo $transaction["change_class"]; ?>">
										<?php
											echo ($transaction["change"] > 0 ? "+" : "")
												. $_DB->nice_format($transaction["change"])
													. " " . $data[0]["currency"];
										?>
									</span>
								</td>
								<td>
									<?php echo $_DB->nice_format($transaction["amount"]); ?> <?php echo $data[0]["currency"]; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
	</div>
</article>