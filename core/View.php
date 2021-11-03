<?php

namespace Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;
use Core\Route;

class View
{
    public static function render($file, $args = [])
    {
        $loader = new FilesystemLoader(__DIR__ . "/../views");
        $twig = new Environment($loader);
        $route = new TwigFunction('route', function($name, $params = null) {
            return Route::url($name, $params);
        });
        $twig->addFunction($route);
        $session = new TwigFunction('session', function($name) {
            return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
        });
        $twig->addFunction($session);
        echo $twig->render("$file.twig", $args);
    }
}