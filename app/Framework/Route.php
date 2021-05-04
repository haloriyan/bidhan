<?php

namespace App\Framework;

class Route {
    private static $_instance = null;
    public static $routes = [];
    public static $prefix = null;
    public static $middlewareGroup = null;

    public static function get($path, $action) {
        if (self::$prefix != null) {
            $path = self::$prefix . "/" . $path;
        }

        $toSave = [
            "http_method" => "GET",
            "path" => $path,
        ];
        if (self::$middlewareGroup != null) {
            $toSave['middleware'] = self::$middlewareGroup;
        }

        if (gettype($action) == "string") {
            $act = explode("@", $action);
            $toSave['controller'] = $act[0];
            $toSave['action'] = $act[1];
        }else {
            $toSave['action'] = $action;
        }

        array_push(self::$routes, $toSave);
        
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public static function post($path, $action) {
        if (self::$prefix != null) {
            $path = self::$prefix . "/" . $path;
        }
        $toSave = [
            "http_method" => "POST",
            "path" => $path,
        ];
        if (self::$middlewareGroup != null) {
            $toSave['middleware'] = self::$middlewareGroup;
        }

        $act = explode("@", $action);
        $toSave['controller'] = $act[0];
        $toSave['action'] = $act[1];

        array_push(self::$routes, $toSave);

        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function render() {
        return self::$routes;
    }
    public function name($routeName) {
        $indexRoute = count(self::$routes) - 1;
        self::$routes[$indexRoute]['name'] = $routeName;
        
        return $this;
    }
    public function middleware($name) {
        $indexRoute = count(self::$routes) - 1;
        self::$routes[$indexRoute]['middleware'] = $name;
        
        return $this;
    }
    public function group($groupInfo, $callback) {
        self::$prefix = $groupInfo['prefix'];
        if (array_key_exists('middleware', $groupInfo)) {
            self::$middlewareGroup = $groupInfo['middleware'];
        }
        $callback();
        self::$prefix = null;
        self::$middlewareGroup = null;
    }
}
