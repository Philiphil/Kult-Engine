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

namespace KultEngine;

abstract class AbstractSession
{
    use CoreElementTrait;
    private static ?SecureSessionHandler $_handler = null;
    public static ?string $_login_page = null;

    public static bool $_login = false;
    public static int $_destroyed = 0;
    public static $_val = null;

    public static function setter()
    {
        static::$_handler = new SecureSessionHandler();
        static::$_handler->start();

        static::$_login_page = static::$_login_page == null ? constant('loginpage') : static::$_login_page;

        $s = new secureSerial();
        static::$_val = isset($_SESSION['val']) ? $s->unserialize($_SESSION['val']) : [];
    }

    public static function destroy()
    {
        unset($_SESSION['login']);
        unset($_SESSION['val']);
        static::$_handler->destroy();
    }

    public static function login_required()
    {
        if (static::get('login')) {
            if (!static::is_on_login_page()) {
                redirect(constant('htmlpath').static::$_login_page, 0);
                exit;
            }
        }
    }

    public static function is_on_login_page(): bool
    {
        return substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], DIRECTORY_SEPARATOR) + 1) === static::$_login_page;
    }

    public static function close()
    {
        static::$_handler->close();
    }

    public static function login()
    {
        static::set('login', true);
    }

    public static function set($k, $v)
    {
        $s = new secureSerial();
        $b = isset($_SESSION['val']) ? $s->unserialize($_SESSION['val']) : [];
        $b[$k] = $v;
        $_SESSION['val'] = $s->serialize($b);
        static::$_val = $b;
    }

    public static function get($k)
    {
        if (!isset(static::$_val) || !isset(static::$_val[$k])) {
            return null;
        }

        return static::$_val[$k];
    }
}
