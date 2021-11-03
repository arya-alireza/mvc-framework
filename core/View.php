<?php

namespace Core;

use App\Helpers\Auth;
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
        $auth = new TwigFunction('auth', function() {
            return Auth::check();
        });
        $twig->addFunction($auth);
        $user = new TwigFunction('user', function() {
            return Auth::user();
        });
        $twig->addFunction($user);
        echo $twig->render("$file.twig", $args);
    }
}