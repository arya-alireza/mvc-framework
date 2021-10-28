<?php

namespace Core;

use Core\Config;

class Route
{
    static $routes = [];

    static $params = [];

    static function get($route, $params)
    {
        self::add($route, $params, 'GET');
    }

    static function post($route, $params)
    {
        self::add($route, $params, 'POST');
    }

    static function add($route, $params, $method)
    {
        $route = preg_replace("/\//", "\/", $route);
        preg_match_all("/\{([a-z]+)\}/", $route, $qCount);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-0-9]+)', $route);
        $route = '/^' . $route . '$/i';
        $npr = [];
        $use = explode("@", $params[0]);
        $npr['controller'] = $use[0];
        $npr['action'] = $use[1];
        $npr['method'] = $method;
        $npr['name'] = $params[1];
        $npr['middleware'] = isset($params[2]) ? $params[2] : false;
        $npr['parameters'] = isset($qCount[1][0]) ? $qCount[1] : false;
        self::$routes[$route] = $npr;
    }

    static function getRoutes()
    {
        return self::$routes;
    }

    static function match($url)
    {
        foreach (self::getRoutes() as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                self::$params = $params;
                $query = new \stdClass();
                foreach ($matches as $key => $match) {
                    if (is_string($key)) $query->$key = $match;
                }
                self::$params['query'] = $query;
                return true;
            }
        }
        return false;
    }

    static function execute($url)
    {
        $url = self::remQueryStrings($url);
        if (self::match($url)) {
            if ($_SERVER['REQUEST_METHOD'] == self::$params['method']) {
                $controller = self::getNameSpace('ctrl') . self::$params['controller'];
                if (class_exists($controller)) {
                    $con_object = new $controller();
                    $action = self::$params['action'];
                    if (method_exists($con_object, $action)) {
                        if (self::$params['middleware']) {
                            $middleware = self::getNameSpace('mlw') . self::$params['middleware'];
                            $mlw = new $middleware();
                            $res = $mlw->handle();
                            if ($res) {
                                if (isset(self::$params['query'])) {
                                    $con_object->$action(self::$params['query']);
                                } else {
                                    $con_object->$action();
                                }
                            } else {
                                $mlw->error();
                            }
                        } else {
                            if (isset(self::$params['query'])) {
                                $con_object->$action(self::$params['query']);
                            } else {
                                $con_object->$action();
                            }
                        }
                    } else {
                        echo "Method not found!";
                    }
                } else {
                    echo "Controller not found!";
                }
            } else {
                echo $_SERVER['REQUEST_METHOD'] . " method is not support!";
            }
        } else {
            echo "404 not found!";
        }
    }

    static function getNameSpace($type)
    {
        if ($type == "ctrl") {
            $namespace = "App\Controllers\\";
        } elseif ($type == "mlw") {
            $namespace = "App\Middlewares\\";
        }
        return $namespace;
    }

    static function find($name)
    {
        foreach (self::$routes as $key => $route) {
            if (in_array($name, $route)) return $key;
        }
        return false;
    }

    static function options($name)
    {
        foreach (self::$routes as $route) {
            if (in_array($name, $route)) return $route;
        }
        return false;
    }

    static function getAppUrl()
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

    static function url($name, $args = null)
    {
        $url = self::getAppUrl();
        $key = self::find($name);
        $opts = self::options($name);
        if (self::find($name)) {
            $key = str_replace("$/i", "", $key);
            $key = str_replace("/^", "", $key);
            $key = str_replace("\/", "/", $key);
            $key = str_replace("[a-z-0-9]+)", "", $key);
            if (isset($args) && is_array($args) && count($args) == count($opts['parameters'])) {
                $i = 0;
                foreach ($opts['parameters'] as $p) {
                    $key = preg_replace("/[(?P<[$p]+>/i", $args[$i], $key);
                    $i++;
                }
            } elseif (isset($args)) {
                $key = preg_replace("/[(?P<[a-z]+>/i", $args, $key);
            }
            return $url . $key;
        } else {
            return false;
        }
    }

    static function remQueryStrings($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    static function redirect($url)
    {
        header("Location: $url");
    }
}