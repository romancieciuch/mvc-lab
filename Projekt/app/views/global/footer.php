	<footer class="footer">
		<div class="footer-container">

			<nav class="footer-links">
				<?php if (!empty($user->logged_in)): ?>
					<a href="/dashboard/">Dashboard</a>

				<?php else: ?>

					<a href="/registration/">Rejestracja</a>
					<a href="/login/">Logowanie</a>
				<?php endif; ?>
				<a href="/contact/">Kontakt</a>
			</nav>

			<div class="footer-copyright">
				&copy; <?php echo date("Y"); ?> Flow. Wszelkie prawa zastrzeżone.
			</div>
		</div>
	</footer>

	<?php if (!empty($user->logged_in)): ?>

		<?php
			if (empty($account_id))
				$account_id = $_USER->get_user_first_account_id($user->id) ?? 0;
		?>

		<nav class="mobile-bottom-nav">
			<a href="/dashboard" class="nav-item">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
					<polyline points="9 22 9 12 15 12 15 22"></polyline>
				</svg>
				<span>Pulpit</span>
			</a>

			<a href="/dashboard" class="nav-item<?php echo ($_ROUTING["controller"] === "dashboard") ? ' active' : ''; ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
					<path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
					<path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
				</svg>
				<span>Konta</span>
			</a>

			<a href="/transaction/0/create/?account-id=<?php echo $account_id ?? 0; ?>" class="nav-item nav-fab">
				<div class="fab-button">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
						<line x1="12" y1="5" x2="12" y2="19"></line>
						<line x1="5" y1="12" x2="19" y2="12"></line>
					</svg>
				</div>
			</a>

			<a href="<?php echo (!empty($account_id)) ? '/account/'.$account_id.'/transactions/' : '#'; ?>" class="nav-item<?php echo ($_ROUTING["controller"] === "account" && $_ROUTING["params"][2] === "transactions") ? ' active' : ''; ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<line x1="8" y1="6" x2="21" y2="6"></line>
					<line x1="8" y1="12" x2="21" y2="12"></line>
					<line x1="8" y1="18" x2="21" y2="18"></line>
					<line x1="3" y1="6" x2="3.01" y2="6"></line>
					<line x1="3" y1="12" x2="3.01" y2="12"></line>
					<line x1="3" y1="18" x2="3.01" y2="18"></line>
				</svg>
				<span>Historia</span>
			</a>

			<a href="/profile" class="nav-item<?php echo ($_ROUTING["controller"] === "profile") ? ' active' : ''; ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
					<circle cx="12" cy="7" r="4"></circle>
				</svg>
				<span>Profil</span>
			</a>
		</nav>
	<?php endif; ?>
</body>
</html>