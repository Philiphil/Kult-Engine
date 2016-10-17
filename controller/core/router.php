<?php

namespace kult_engine;

use kult_engine as k;

class router
{
    use debugable;
    use singleton;
    public static $_a_asked;
    public static $_asked;
    public static $_method;
    public static $_route;
    public static $_global_route;
    public static $_argex = '|<!';
    private static $_auto_executor;
    public static $_global_routing = 1;

    public static function setter()
    {
        self::$_method = strtoupper($_SERVER['REQUEST_METHOD']);
        self::$_asked = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
        self::$_a_asked = self::read_asked(self::$_asked);
        self::$_route = [];
        self::$_auto_executor = new router_executor();
    }

    public static function read_asked($brut)
    {
        if (k\contains('|<\\_**_', $brut)) {
            trigger_error('"|<\\_**_" is reserved by kult_engine\\router', E_USER_ERROR);
                //since you cant get something containing a \, it shouldnt happen
        }
        if ($brut === '*' || $brut === '') {
            return ['|<\\_**_'];
        }
        if (substr($brut, 0, 1) !== '/') {
            return;
        }
        $brut = substr($brut, 1);
        if (!k\contains('/', $brut)) {
            return $brut != '' ? [$brut] : ['*'];
        }
        $array = explode('/', $brut);

        if ($array[count($array) - 1] === '') {
            unset($array[count($array) - 1]);
        }

        return $array;
    }

    public static function set_route($route, $func, $method = 'GET')
    {
        self::$_route[count(self::$_route)] = [$route, $func, strtoupper($method)];
    }

    public static function exec()
    {
        if (self::$_global_routing) {
            self::disable_global_routing();
            foreach (self::$_global_route as $route) {
                self::exec_route($route);
            }
        }

        foreach (self::$_route as $route) {
            self::exec_route($route);
        }
    }

    public static function exec_route($route)
    {
        if ($route[2] === self::$_method) {
            $tmp = self::is_route_applicable($route[0]);
            if ($tmp !== 0) {
                call_user_func_array($route[1], $tmp);
            }
        }
    }

    public static function disable_global_routing($bool = 0)
    {
        self::$_global_routing = $bool;
    }

    public static function is_route_applicable($route)
    {
        $translated_route = self::read_asked($route);
        $args = [];

        if (count($translated_route) === 1 && $translated_route[0] === '|<\\_**_') {
            return $args;
        }

        if (count($translated_route) > count(self::$_a_asked) && (count($translated_route) - 1 == count(self::$_a_asked) && $translated_route[count($translated_route) - 1] != '*')) {
            // if route is longuer than uri, route is probably not applicable
                //and if route is just 1 arg longuer than uri, this arg has to be *
                return 0;
        }

        for ($i = 0; $i < count($translated_route); $i++) {
            if ($translated_route[$i] != '*' && !k\contains(self::$_argex, $translated_route[$i]) && $translated_route[$i] != self::$_a_asked[$i]) {
                var_dump($translated_route);
                k\echo_br();
                var_dump(self::$_a_asked);

                return 0;
            }
            if (k\contains(self::$_argex, $translated_route[$i])) {
                $args[intval(substr($translated_route[$i], strlen(self::$_argex)))] = self::$_a_asked[$i];
            }
        }
        if ($translated_route[0] === '*' && self::$_a_asked[0] === '|<\\_**_') {
            return 0;
        }

        return $args;
    }
}


    class router_executor
    {
        public function __destruct()
        {
            router::exec();
        }
    }

    class global_route
    {
        public $_route;

        public function __construct($route, $func, $method = 'GET')
        {
            router::$_global_route[count(router::$_global_route)] = [$route, $func, $method];
        }
    }
