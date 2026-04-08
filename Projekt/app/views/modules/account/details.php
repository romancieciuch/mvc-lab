<article class="article page-width">
	<h1 class="article-title">
		<small class="article-title-breadcrumb">Szczegóły konta:</small>
		<?php echo $data[0]["name"]; ?>
	</h1>

	<div class="page-width__narrow">
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
	</div>

	<p class="acenter">
		<a class="button" href="/account/<?php echo $data[0]["id"]; ?>/edit/">Edytuj</a>
	</p>

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
								<?php echo $_DB->nice_format($transaction["balance"]); ?> <?php echo $data[0]["currency"]; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>