<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../routes/web.php';

if (! isset($_SESSION)) session_start();

use Core\Route;

Route::execute($_SERVER['QUERY_STRING']);