<?php

	$transaction = new App\Models\Transaction($_DB);
	$transactions = $transaction->get_transactions(
		$account_id,
		$user->id,
		$pagination["limit"],
		$pagination["offset"]
	);