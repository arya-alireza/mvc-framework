<?php

use Core\View;

if (! function_exists('view')) {
    function view($name, $args = []) {
        View::render($name, $args);
    }
}