<article class="article page-width__narrow">
	<h1 class="article-title">
		<small class="article-title-breadcrumb">Szczegóły konta:</small>
		<?php echo $data[0]["name"]; ?>
	</h1>

	<div class="table-container">
		<table class="table">
			<tbody>
				<tr>
					<th>Konto</th>
					<td><?php echo $data[0]["name"]; ?></td>
				</tr>
				<tr>
					<th>Saldo</th>
					<td>
						<span class="balance-amount<?php if ($data[0]["balance"] < 0) echo ' is-negative' ?>"><?php echo $_DB->nice_format($data[0]["balance"]); ?></span>
						<span class="balance-currency"><?php echo $data[0]["currency"]; ?></span>
					</td>
				</tr>
				<tr>
					<th>Priorytet</th>
					<td>
						<?php echo $data[0]["priority"]; ?> / 100
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<?php if (!empty($data[0]["description"])): ?>
		<div class="article-body">
			<h2>Opis transakcji</h2>
			<p><?php echo nl2br($data[0]["description"]); ?></p>
		</div>
	<?php endif; ?>

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
					<?php
						$last_amount = end($history)["balance"] ?? 0;
						$i = 0;

						foreach ($history as $transaction):
							$change = $transaction["balance"] - $last_amount;
							if ($i === count($history) - 1) $change = 0;
					?>
							<tr>
								<td>
									<?php echo $transaction["log_date"]; ?>
								</td>
								<td>
									<span class="<?php echo ($change < 0) ? "is-negative" : "is-positive"; ?>">
										<?php echo $_DB->nice_format($change); ?> <?php echo $data[0]["currency"]; ?>
									</span>
								</td>
								<td>
									<?php echo $_DB->nice_format($transaction["balance"]); ?> <?php echo $data[0]["currency"]; ?>
								</td>
							</tr>
					<?php
							$last_amount = $transaction["balance"];
							$i++;
						endforeach;
					?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>

	<p class="acenter"><a class="back-button" href="/dashboard/">Powrót</a></p>
</article>