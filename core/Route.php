<?php

namespace Core;

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
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-0-9]+)', $route);
        $route = '/^' . $route . '$/i';
        $npr = [];
        $use = explode("@", $params[0]);
        $npr['controller'] = $use[0];
        $npr['action'] = $use[1];
        $npr['method'] = $method;
        $npr['name'] = $params[1];
        $npr['middleware'] = isset($params[2]) ? $params[2] : false;
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
                foreach ($matches as $key => $match) {
                    if (is_string($key)) self::$params['query'][$key] = $match;
                }
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

    static function url($name)
    {
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/";
        $key = self::find($name);
        if (self::find($name)) {
            return $url . str_replace("/^", "", str_replace("$/i", "", $key));
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
}