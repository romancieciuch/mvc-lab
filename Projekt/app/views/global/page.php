<?php

	require_once "header.php";

	if (is_array($modules))
		foreach ($modules as $m)
			if (file_exists($m))
				require_once $m;

	require_once "footer.php";