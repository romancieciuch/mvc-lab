<?php

declare(strict_types=1);
ini_set('session.gc_maxlifetime', 604800);

session_set_cookie_params([
    'lifetime' => 604800,
    'path'     => '/',
    'domain'   => '',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();
ob_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/../app/init.php";