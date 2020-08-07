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

abstract class AbstractSession
{
    public static ?string $_login_page = null;
    public static $_login = 0;
    public static $_token_1 = null;
    public static $_token_2 = null;
    public static $_val = null;

    public static function setter()
    {
        session_start();
        self::$_login_page = self::$_login_page == null ? constant('loginpage') : self::$_login_page;
        if (isset($_SESSION['login']) && password_verify($_SERVER['HTTP_USER_AGENT'], $_SESSION['token_1']) && password_verify('_S7aTic_:p=rm@tK3y;', $_SESSION['token_2'])) {
            self::$_login = intval($_SESSION['login']);
            self::$_token_1 = $_SESSION['token_1'];
            self::$_token_2 = $_SESSION['token_2'];
            self::$_val = isset($_SESSION['val']) ? $_SESSION['val'] : [];
        } else {
            self::destroy();
        }
    }

    public static function setter_conf($fnord)
    {
        session_start();
        self::$_login_page = constant('loginpage');
        if (isset($_SESSION['login']) && password_verify($_SERVER['HTTP_USER_AGENT'], $_SESSION['token_1']) && password_verify('_S7aTic_:p=rm@tK3y;', $_SESSION['token_2'])) {
            self::$_login = intval($_SESSION['login']);
            self::$_token_1 = $_SESSION['token_1'];
            self::$_token_2 = $_SESSION['token_2'];
            self::$_val = $_SESSION['val'];
        } else {
            self::destroy();
        }
    }

    public static function destroy()
    {
        unset($_SESSION['login']);
        unset($_SESSION['token_1']);
        unset($_SESSION['token_2']);
        unset($_SESSION['val']);
    }

    public static function login_required()
    {
        if (!isset($_SESSION['login'])) {
            if (!static::is_on_login_page()) {
                redirect(constant('htmlpath').self::$_login_page, 0);
                exit;
            }
        }
    }

    public static function is_on_login_page(): bool
    {
        return substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], DIRECTORY_SEPARATOR) + 1) === static::$_login_page;
    }

    public static function end()
    {
        session_write_close();
    }

    public static function connexion()
    {
        $_SESSION['login'] = 1;
        $_SESSION['token_1'] = password_hash($_SERVER['HTTP_USER_AGENT'], PASSWORD_BCRYPT);
        $_SESSION['token_2'] = password_hash('_S7aTic_:p=rm@tK3y;', PASSWORD_BCRYPT);
        self::$_login = 1;
        self::$_token_1 = $_SESSION['token_1'];
        self::$_token_2 = $_SESSION['token_2'];
    }

    public static function set($k, $v)
    {
        $s = new secureSerial();
        $b = isset($_SESSION['val']) ? $s->unserialize($_SESSION['val']) : [];
        $b[$k] = $v;
        $_SESSION['val'] = $s->serialize($b);
        self::$_val = $b;
    }

    public static function get($k)
    {
        if (!isset($_SESSION['val'])) {
            return false;
        }
        $s = new secureSerial();
        $b = $s->unserialize($_SESSION['val']);

        return $b === false ? $b : $b[$k];
    }
}
