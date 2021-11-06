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
        $redirect = new TwigFunction('redirect', function($name) {
            return Route::redirect($name);
        });
        $twig->addFunction($redirect);
        $asset = new TwigFunction('asset', function($file) {
            if (Config::APP_URL != "") {
                $url = Config::APP_URL;
            } else {
                $url = $_SERVER['SERVER_PORT'] == 80 ? "http://" : "https://";
                $url .= $_SERVER['SERVER_NAME'];
                $url .= str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
            }
            return $url . $file;
        });
        $twig->addFunction($asset);
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