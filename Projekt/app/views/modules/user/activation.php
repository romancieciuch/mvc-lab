<article class="article page-width__midi">
	<h1 class="article-title">
		Aktywacja konta
	</h1>

	<div class="article-body">
		<?php if (empty($errors)): ?>
			<p>Aktywacja powiodła się. Teraz możesz się <strong><a href="/login/">zalogować</a></strong>.</p>
		<?php else: ?>
			<p><?php echo implode(" ", $errors); ?></p>
		<?php endif;?>
	</div>
</article>