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
</body>
</html>