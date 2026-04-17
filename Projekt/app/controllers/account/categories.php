<?php

	$category = new App\Models\Category($_DB);
	$categories = $category->get_account_categories($account_id);

	$category_types = [
		"default"	=> "Domyślny",
		"income"	=> "Zarobki",
		"expense"	=> "Wydatki",
		"tax"		=> "Podatki"
	];

	if (!empty($data[0]["id"]))
		$prev_page = "/account/" . intval($data[0]["id"]) . "/transactions/";