<?php

	$history = $transaction->get_history($transaction_id);
	$history = $transaction->calculate_history($history, true);

	$reversed_history = array_reverse($history);
	$chart = new App\Models\Chart(["currency" => $data[0]["currency"]]);
	$chart_html = $chart->draw($reversed_history, "log_date", "amount");