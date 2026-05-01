<article class="article page-width">
	<h1 class="article-title">
		Sesje użytkownika
	</h1>

	<div class="tabs-container">
		<nav class="tabs-nav" aria-label="Nawigacja">
			<a href="/profile/" class="tab-item">Twoje dane</a>
			<a href="/profile/password/" class="tab-item">Zmiana hasła</a>
			<a href="/profile/2fa/" class="tab-item">Logowanie dwuetapowe</a>
			<a href="/profile/statistics/" class="tab-item">Statystyka</a>
			<a href="/profile/sessions/" class="tab-item active" aria-current="page">Sesje</a>
		</nav>
	</div>

	<?php if (!empty($sessions)): ?>
		<div class="table-container">
			<table class="table table__mobile-friendly">
				<thead>
					<tr>
						<th>Urządzenie</th>
						<th>Adres IP</th>
						<th>Data utworzenia</th>
						<th>Data ważności</th>
						<th>Akcje</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($sessions as $k=>$v): ?>
						<tr>
							<td class="table-name">
								<?php echo $v["user_agent"]; ?>
							</td>
							<td>
								<?php echo $v["ip_address"]; ?>
							</td>
							<td>
								<?php echo $v["created_at"]; ?>
							</td>
							<td>
								<?php echo $v["expires_at"]; ?>
							</td>
							<td class="table-options table-options-has-menu">
								<div class="table-options-menu">
									<a class="accent" href="/profile/sessions/<?php echo $v["id"]; ?>/delete/">Usuń</a>
								</div>
								<button class="table-options-button"></button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

	<?php else: ?>
		<div class="page-width__narrow">
			<div class="article-body">
				<p>Brak sesji użytkownika.</p>
			</div>
		</div>
	<?php endif; ?>

	<p class="acenter"><a class="back-button" href="<?php echo $prev_page; ?>">Powrót</a></p>
</article>