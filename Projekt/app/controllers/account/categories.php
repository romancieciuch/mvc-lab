<?php

	$category = new App\Models\Category($_DB);
	$categories = $category->get_all_categories($user->id);