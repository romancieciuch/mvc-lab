<article class="article page-width__midi">
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
			if (!empty($user->two_factor_auth))
				echo $_FORM->global_message([
					"title" => "Twoje logowanie dwuetapowe jest włączone."
				]);
			else
				echo $_FORM->global_info([
					"title" => "Twoje logowanie dwuetapowe jest wyłączone."
				]);
		?>

		<p>
			W tym miejscu możesz wygenerować nowy kod, który następnie zeskanujesz
			w aplikacji Google Authenticator lub jej odpowiednikom.
		</p>
		<p>
			Kliknięcie przycisku - <strong>Wygeneruj kod QR</strong> - wygeneruje nowy kod
			i od razu przypisze go do Twojego konta. Zatem, od następnego logowania będzie wymagane posiadanie
			aplikacji Google Authenticator i odczytanie z niej aktualnego kodu.
		</p>
		<p>
			Jeśli coś pójdzie nie tak, i / lub chcesz zrezygnować z logowania dwuetapowego
			- wybierz opcję: <strong>Wyłącz logowanie dwuetapowe</strong>.
		</p>

		<h2 class="acenter">Wybierz jedną z opcji:</h2>
		<p class="buttons">
			<a href="/profile/2fa/generate/" class="button">Wygeneruj kod QR</a>
			<a href="/profile/2fa/deactivate/" class="button button-accent">Wyłącz logowanie dwuetapowe</a>
		</p>
	</div>

	<p class="acenter"><a class="back-button" href="/profile/">Powrót</a></p>
</article>