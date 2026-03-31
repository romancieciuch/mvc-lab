<article class="article page-width">
	<h1 class="article-title">
		Dashboard
	</h1>

	<?php if (!empty($data)): ?>
		<div class="table-container">
			<table class="table table__mobile-friendly">
				<thead>
					<tr>
						<th>Konto</th>
						<th>Data utworzenia</th>
						<th>Saldo</th>
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
								<?php echo $v["created_at"]; ?>
							</td>
							<td>
								<?php echo $_DB->nice_format($v["balance"]); ?> <?php echo $v["currency"]; ?>
							</td>
							<td class="table-options">
								<a href="/account/<?php echo $v["id"]; ?>/edit/">Edytuj</a>
								<a href="/account/<?php echo $v["id"]; ?>/categories/">Kategorie</a>
								<a class="accent" href="/account/<?php echo $v["id"]; ?>/delete/">Usuń</a>
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