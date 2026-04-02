<article class="article page-width">
	<div class="article-title-wrap">
		<h1 class="article-title">
			<small class="article-title-breadcrumb">Kategorie konta:</small>
			<?php echo $data[0]["name"]; ?>
		</h1>

		<div class="article-title-actions">
			<a href="/category/0/create/?account-id=<?php echo $data[0]["id"]; ?>" class="button">Nowa kategoria</a>
		</div>
	</div>

	<?php if (!empty($categories)): ?>
		<div class="table-container">
			<table class="table table__mobile-friendly">
				<thead>
					<tr>
						<th>Nazwa</th>
						<th>Data utworzenia</th>
						<th>Akcje</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($categories as $k=>$v): ?>
						<tr>
							<td class="table-name">
								<span class="color-box" style="background-color: <?php echo $v["color"]; ?>;"></span>
								<a href="/category/<?php echo $v["id"]; ?>/details/">
									<?php echo $v["name"]; ?>
								</a>
							</td>
							<td><?php echo $v["created_at"]; ?></td>
							<td class="table-options">
								<a href="/category/<?php echo $v["id"]; ?>/details/?account-id=<?php echo $data[0]["id"]; ?>">Szczegóły</a>
								<a href="/category/<?php echo $v["id"]; ?>/edit/?account-id=<?php echo $data[0]["id"]; ?>">Edytuj</a>
								<a class="accent" href="/category/<?php echo $v["id"]; ?>/delete/?account-id=<?php echo $data[0]["id"]; ?>">Usuń</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<div class="article-body page-width__narrow">
			<p>Nie masz jeszcze żadnych kategorii. Dodaj coś.</p>
		</div>
	<?php endif; ?>

	<p class="acenter"><button class="back-button" onclick="history.back()">Powrót</button></p>
</article>