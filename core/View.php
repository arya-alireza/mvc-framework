<?php

namespace Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFilter;
use Core\Route;

class View
{
    public static function render($file, $args = [])
    {
        $loader = new FilesystemLoader(__DIR__ . "/../views");
        $twig = new Environment($loader);
        $route = new TwigFilter('route', function($name) {
            return Route::url($name);
        });
        $twig->addFilter($route);
        echo $twig->render("$file.twig", $args);
    }
}