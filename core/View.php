<?php

namespace Core;

use App\Helpers\Auth;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;
use Core\Route;

class View
{
    public static function getUrl()
    {
        if (Config::APP_URL != "") {
            $url = Config::APP_URL;
        } else {
            $url = $_SERVER['SERVER_PORT'] == 80 ? "http://" : "https://";
            $url .= $_SERVER['SERVER_NAME'];
            $url .= str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
        }
        return $url;
    }

    public static function functions()
    {
        $opts = [];
        $opts['route'] = new TwigFunction('route', function($name, $params = null) {
            return Route::url($name, $params);
        });
        $opts['redirect'] = new TwigFunction('redirect', function($name) {
            return Route::redirect($name);
        });
        $opts['asset'] = new TwigFunction('asset', function($file) {
            return self::getUrl() . "assets/" . $file;
        });
        $opts['session'] = new TwigFunction('session', function($name) {
            return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
        });
        $opts['auth'] = new TwigFunction('auth', function() {
            return Auth::check();
        });
        $opts['user'] = new TwigFunction('user', function() {
            return Auth::user();
        });
        $opts['count'] = new TwigFunction('count', function($arr) {
            return count($arr);
        });
        return $opts;
    }

    public static function render($file, $args = [])
    {
        $loader = new FilesystemLoader(__DIR__ . "/../resources/views");
        $twig = new Environment($loader);
        foreach (self::functions() as $item) $twig->addFunction($item);
        echo $twig->render("$file.twig", $args);
    }
}