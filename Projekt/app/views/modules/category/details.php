<article class="article page-width__narrow">
	<h1 class="article-title">
		Szczegóły kategorii: <?php echo $data[0]["name"]; ?>
	</h1>

	<div class="table-container">
		<table class="table">
			<tbody>
				<tr>
					<th>Konto</th>
					<td><?php echo $data[0]["account_name"]; ?></td>
				</tr>
				<tr>
					<th>Kolor</th>
					<td>
						<span class="color-box" style="background: <?php echo $data[0]["color"]; ?>"></span>
						<?php echo $data[0]["color"]; ?>
					</td>
				</tr>
				<tr>
					<th>Data utworzenia</th>
					<td>
						<?php echo date("Y-m-d", strtotime($data[0]["created_at"])); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<p class="acenter"><strong><a href="/dashboard/">Powrót</a></strong></p>
</article>