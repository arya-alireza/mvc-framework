<?php

use Core\View;
use Core\Route;

if (! function_exists('view')) {
    function view($name, $args = []) {
        View::render($name, $args);
    }
}

if (! function_exists('route')) {
    function route($name, $args = null) {
        Route::url($name, $args);
    }
}

if (! function_exists('redirect')) {
    function redirect($url, $msg = null) {
        Route::redirect($url, $msg);
    }
}