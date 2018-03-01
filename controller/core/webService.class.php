<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016-2017
 *
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
 * @copyright Copyright (c) 2016-2017, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

abstract class webService
{
    use coreElement;
    use hookable;

    public static $_req;
    public static $_args;
    public static $_method;
    public static $_token;
    public static $_func = [];


    public static function setter()
    {
        self::$_req = isset($_POST['req']) ? $_POST['req'] : (isset($_GET['req']) ? $_GET['req'] : false);
        self::$_args = isset($_POST['args']) ? json_decode($_POST['args'], true) : (isset($_GET['args']) ? json_decode($_GET['args'], true) : false);
        self::$_token = isset($_POST['token']) ? $_POST['token'] : (isset($_GET['token']) ? $_GET['token'] : false);
        self::$_method = $_SERVER['REQUEST_METHOD'];
    }

    public static function send($array = [])
    {
        $array['token'] = self::$_token;
        echo json_encode($array);
    }

    public static function execute()
    {
        if (isset(self::$_func[self::$_method][self::$_req])) {
            self::send(self::$_func[self::$_method][self::$_req](self::$_args));
        }
    }

    public static  function service($a, $c, $t = 'POST')
    {
        self::$_func[$t][$a] = $c;
    }

    public static function destruct()
    {
        return [['kult_engine\\webService::execute', null], 2];
    }
}
