<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Kult Engine
 * @author Théo Sorriaux (philiphil)
 * @copyright Copyright (c) 2016-2018, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

use kult_engine as k;

abstract class router
{
    use coreElement;
    use hookable;
    public static $_a_asked = [];
    public static $_asked = '';
    public static $_method = 'GET';
    public static $_route = [];
    public static $_global_route = [];
    public static $_argex = '$';
    private static $_auto_executor = null;
    public static $_global_routing = 1;
    public static $_code = 200;

    public static function setter()
    {
        self::$_method = strtoupper($_SERVER['REQUEST_METHOD']);
        self::$_asked = strpos($_SERVER['REQUEST_URI'], '?') > -1 ? substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) : $_SERVER['REQUEST_URI'];
        self::$_a_asked = self::read_asked(self::$_asked);
        self::$_route = [];
        self::$_code = http_response_code();
    }

    public static function destruct()
    {
        return [['kult_engine\\router::exec', null], 1];
    }

    public static function read_asked($brut)
    {
        self::init_required();

        return explode('/', substr($brut, 1));
    }

    public static function set_route($route, $func, $method = 'GET', $code = 200)
    {
        self::init_required();
        self::$_route[count(self::$_route)] = [$route, $func, strtoupper($method), intval($code)];
    }

    public static function exec()
    {
        self::init_required();
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
        self::init_required();
        if (($route[2] === self::$_method || $route[2] === '*') &&
           /* $route[3] === self::$_route*/true) {
            $tmp = self::is_route_applicable($route[0]);
            if ($tmp !== 0) {
                call_user_func_array($route[1], $tmp);
            }
        }
    }

    public static function disable_global_routing($bool = 0)
    {
        self::init_required();
        self::$_global_routing = $bool;
    }

    public static function is_route_applicable($route)
    {
        self::init_required();
        $translated_route = self::read_asked($route);
        $args = [];

        if (count($translated_route) > count(self::$_a_asked) ||
            (count($translated_route) < count(self::$_a_asked)
            && $translated_route[count($translated_route) - 1] != '*')
        ) {
            return 0;
        }

        for ($i = 0; $i < count($translated_route); $i++) {
            if ($translated_route[$i] != '*' &&
                !k\contains(self::$_argex, $translated_route[$i]) &&
                isset(self::$_a_asked[$i]) &&
                $translated_route[$i] != self::$_a_asked[$i]) {
                return 0;
            }
            if (k\contains(self::$_argex, $translated_route[$i])) {
                $args[intval(substr($translated_route[$i], strlen(self::$_argex)))] = isset(self::$_a_asked[$i]) ? self::$_a_asked[$i] : null;
            }
        }
        if ($translated_route[0] === '*') {
            return 0;
        }

        return $args;
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
