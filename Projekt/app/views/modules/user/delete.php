<article class="article page-width__midi">
	<h1 class="article-title">
		Usuwanie konta
	</h1>

	<div class="article-body">
		<?php if (empty($delete)): ?>
			<h2>Czy na pewno chcesz usunąć konto razem ze wszystkimi danymi?</h2>
			<p class="buttons">
				<a href="/" class="button button-accent">NIE</a>
				<a href="?delete" class="button">TAK</a>
			</p>
		<?php else: ?>
			<p>Konto zostało usunięte.</p>
		<?php endif;?>
	</div>
</article>