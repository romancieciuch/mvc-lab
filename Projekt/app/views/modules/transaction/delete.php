<article class="article page-width__midi">
	<h1 class="article-title">
		Usuwanie transakcji
	</h1>

	<div class="article-body">
		<?php if (!empty($data)): ?>
			<h2 class="acenter">
				Czy na pewno chcesz usunąć transakcję: <strong><?php echo $data[0]["name"]; ?></strong>?
			</h2>

			<p class="buttons">
				<a href="/" class="button button-accent">NIE</a>
				<a href="?delete&account-id=<?php echo $_GET["account-id"] ?? ""; ?>" class="button">TAK</a>
			</p>
		<?php else: ?>
			<p>Transakcja została usunięta.</p>
		<?php endif;?>
	</div>

	<p class="acenter"><a class="back-button" href="<?php echo $_APP->prev_page; ?>">Powrót</a></p>
</article>