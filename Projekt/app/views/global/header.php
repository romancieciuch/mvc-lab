<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Flow</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap">

	<link rel="stylesheet" href="/css/styles.css?v=<?php echo filemtime(WWW_DIR . "css/styles.css"); ?>">
	<script src="/js/scripts.js?v=<?php echo filemtime(WWW_DIR . "js/scripts.js"); ?>" defer></script>

	<meta id="meta-theme-color" name="theme-color" content="#2563EB">
	<link rel="icon" type="image/svg+xml" href="/images/flow.svg">
	<link rel="manifest" href="/manifest.json?v=<?php echo filemtime(WWW_DIR . "manifest.json"); ?>">
</head>
<body>
	<header class="header">
		<div class="header-container">
			<a href="/" class="logo">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 163 163"><g transform="translate(-308 389)"><path d="M182.8,162.5H59.8A19.5,19.5,0,0,1,40.3,143V116.416c6.637-2.165,13.484-5.941,20.933-11.544a22.747,22.747,0,0,1,27.678,0c9.126,6.9,20.115,13.955,32.421,13.955,12.149,0,23.207-7.055,32.421-13.955h-.039a22.747,22.747,0,0,1,27.678,0c7.386,5.6,14.225,9.381,20.909,11.565V143a19.5,19.5,0,0,1-19.5,19.5Z" transform="translate(268.2 -389)" fill="#2563eb" stroke="#707070" stroke-width="1"/><path d="M121.293,100.169a21.118,21.118,0,0,1-9.106-2.473,75.721,75.721,0,0,1-12.08-7.751,41.405,41.405,0,0,0-50.147,0,70.2,70.2,0,0,1-9.66,6.236V79.1c6.637-2.165,13.484-5.941,20.933-11.544a22.747,22.747,0,0,1,27.678,0c9.126,6.9,20.115,13.955,32.421,13.955,12.149,0,23.207-7.055,32.421-13.955a22.747,22.747,0,0,1,27.678,0h-.039c7.384,5.6,14.224,9.381,20.909,11.565v17.07a70.745,70.745,0,0,1-9.675-6.244,41.4,41.4,0,0,0-50.146,0A75.719,75.719,0,0,1,130.4,97.7,21.118,21.118,0,0,1,121.293,100.169Z" transform="translate(268.2 -389)" fill="#f97316" stroke="#707070" stroke-width="1"/><path d="M121.293,62.851a21.118,21.118,0,0,1-9.106-2.473,75.721,75.721,0,0,1-12.08-7.751,41.405,41.405,0,0,0-50.147,0,70.2,70.2,0,0,1-9.66,6.236V20A19.5,19.5,0,0,1,59.8.5h123A19.5,19.5,0,0,1,202.3,20V58.871a70.745,70.745,0,0,1-9.675-6.244,41.4,41.4,0,0,0-50.146,0,75.719,75.719,0,0,1-12.08,7.751A21.118,21.118,0,0,1,121.293,62.851Z" transform="translate(268.2 -389)" fill="#2563eb" stroke="#707070" stroke-width="1"/></g></svg>
				FLOW
			</a>

			<nav class="main-nav" id="main-nav">
				<?php if (!empty($user->logged_in)): ?>
					<a href="/dashboard/">Dashboard</a>
					<a href="/profile/">Profil</a>
					<a href="/logout/">Wyloguj</a>

				<?php else: ?>

					<a href="/registration/">Rejestracja</a>
					<a href="/login/">Logowanie</a>
				<?php endif; ?>

				<button id="theme-toggle" class="theme-switch" aria-label="Przełącz motyw">
					<span class="switch-thumb"></span>
				</button>
			</nav>

			<button id="mobile-menu-btn" class="mobile-btn" aria-label="Otwórz menu">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hamburger-icon">
					<line class="line-top" x1="3" y1="6" x2="21" y2="6"></line>
					<line class="line-middle" x1="3" y1="12" x2="21" y2="12"></line>
					<line class="line-bottom" x1="3" y1="18" x2="21" y2="18"></line>
				</svg>
			</button>
		</div>
	</header>