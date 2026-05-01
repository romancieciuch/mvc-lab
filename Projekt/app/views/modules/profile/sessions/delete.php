<article class="article page-width__midi">
	<h1 class="article-title">
		Usuwanie sesji
	</h1>

	<div class="article-body">
		<?php if (!empty($data)): ?>
			<h2 class="acenter">
				Czy na pewno chcesz usunąć sesję: <strong><?php echo $data["user_agent"]; ?></strong>?
			</h2>

			<p class="buttons">
				<a href="/profile/sessions/" class="button button-accent">NIE</a>
				<a href="?delete" class="button">TAK</a>
			</p>
		<?php else: ?>
			<p>Sesja została usunięta.</p>
		<?php endif;?>
	</div>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>