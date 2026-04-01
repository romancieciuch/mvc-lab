<?php

	$category = new App\Models\Category($_DB);
	$categories = $category->get_account_categories($account_id);