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
</head>
<body>
	<header class="header">
		<div class="header-container">
			<a href="/" class="logo">FLOW</a>

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