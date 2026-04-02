<article class="article page-width__midi">
	<h1 class="article-title">
		Usuwanie kategorii
	</h1>

	<div class="article-body">
		<?php if (!empty($data)): ?>
			<h2 class="acenter">
				Czy na pewno chcesz usunąć kategorię: <strong><?php echo $data[0]["name"]; ?></strong>?
			</h2>

			<p class="buttons">
				<a href="/" class="button button-accent">NIE</a>
				<a href="?delete&account-id=<?php echo $_GET["account-id"] ?? ""; ?>" class="button">TAK</a>
			</p>
		<?php else: ?>
			<p>Kategoria została usunięta.</p>
		<?php endif;?>
	</div>

	<p class="acenter">
		<?php if (!empty($_GET["account-id"])): ?>
			<a href="/account/<?php echo $_GET["account-id"]; ?>/categories/" class="back-button">Powrót</a>
		<?php else: ?>
			<a href="/dashboard/" class="back-button">Powrót</a>
		<?php endif; ?>
	</p>
</article>