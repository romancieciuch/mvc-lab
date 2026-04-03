<?php

	$history = $account->get_history($user->id, $account_id);
	$history = $account->calculate_history($history, true);