<article class="article page-width__narrow">
	<h1 class="article-title">
		Logowanie dwuetapowe
	</h1>

	<div class="tabs-container">
		<nav class="tabs-nav" aria-label="Nawigacja">
			<a href="/profile/" class="tab-item">Twoje dane</a>
			<a href="/profile/password/" class="tab-item">Zmiana hasła</a>
			<a href="/profile/2fa/" class="tab-item active" aria-current="page">Logowanie dwuetapowe</a>
		</nav>
	</div>

	<div class="article-body">
		<?php
			echo $_FORM->global_message([
				"title" => "Logowanie dwuetapowe zostało wyłączone"
			]);
		?>
	</div>

	<p class="acenter"><a class="back-button" href="/profile/2fa/">Powrót</a></p>
</article>