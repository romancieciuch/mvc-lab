<?php

declare(strict_types=1);
ini_set("session.gc_maxlifetime", 3600);

session_set_cookie_params([
    "lifetime" => 0,
    "path"     => "/",
    "domain"   => "",
    "secure"   => true,
    "httponly" => true,
    "samesite" => "Lax"
]);

session_start();
ob_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/../app/init.php";