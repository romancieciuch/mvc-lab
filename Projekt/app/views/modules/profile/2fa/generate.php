<article class="article page-width__narrow">
	<h1 class="article-title">
		Logowanie dwuetapowe
	</h1>

	<div class="tabs-container">
		<nav class="tabs-nav" aria-label="Nawigacja">
			<a href="/profile/" class="tab-item">Twoje dane</a>
			<a href="/profile/password/" class="tab-item">Zmiana hasła</a>
			<a href="/profile/2fa/" class="tab-item active" aria-current="page">Logowanie dwuetapowe</a>
			<a href="/profile/statistics/" class="tab-item">Statystyka</a>
		</nav>
	</div>

	<div class="article-body">
		<h2 class="acenter">Zeskanuj ten kod w aplikacji Google Authenticator</h2>

		<div class="qrcode">
			<?php echo $qrcode_svg; ?>
		</div>

		<?php
			echo $_FORM->global_info([
				"title" => "Zapisz swój kod zapasowy w bezpiecznym miejscu",
				"desc" => "<strong>{$secret}</strong>"
			]);
		?>

		<p class="acenter">
			Cały czas możesz:
			<br>
			<strong>
				<a href="/profile/2fa/deactivate/" class="accent">
					wyłączyć logowanie dwuetapowe
				</a>
			</strong>
		</p>
	</div>

	<p class="acenter"><a class="back-button" href="/profile/2fa/">Powrót</a></p>
</article>