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
				<a href="?delete" class="button">TAK</a>
			</p>
		<?php else: ?>
			<p>Kategoria została usunięta.</p>
		<?php endif;?>
	</div>

	<p class="acenter"><strong><a href="/dashboard/">Powrót</a></strong></p>
</article>