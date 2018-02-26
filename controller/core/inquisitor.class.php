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

abstract class inquisitor
{
    use coreElement;
    public static $_tempo = [];
    public static $_watcher = 0;
    public static $_flag = 0;
    public static $_deny = 0;

    public static function setter()
    {
        self::$_watcher = isset($_SESSION['watcher']) ? intval($_SESSION['watcher']) : 0;
        self::$_flag = isset($_SESSION['flag']) ? intval($_SESSION['flag']) : 0;
        self::$_deny = isset($_SESSION['deny']) ? intval($_SESSION['deny']) : 0;
        self::$_tempo = isset($_SESSION['tempo']) ? $_SESSION['tempo'] : [];
        self::compute();
    }

    public static function save()
    {
        self::init_required();
        $_SESSION['watcher'] = self::$_watcher;
        $_SESSION['flag'] = self::$_flag;
        $_SESSION['deny'] = self::$_deny;
        $_SESSION['tempo'] = self::$_tempo;
    }

    public static function compute()
    {
        self::init_required();
        if (isset(self::$_tempo['time'])) {
            if (time() - self::$_tempo['time'] > 60 * 5) {
                unset(self::$_tempo['time']);
                unset(self::$_tempo['flags']);
            } else {
                self::$_watcher += self::$_tempo['flags'] / 3 > 1 ? intval(round(self::$_tempo['flags'] / 3)) : 0;
                self::$_tempo['flags'] = intval(round(self::$_tempo['flags'])) / 3 > 1 ? self::$_tempo['flags'] % 3 : self::$_tempo['flags'];
            }
        }
        self::$_flag += self::$_watcher / 3 > 1 ? intval(round(self::$_watcher / 3)) : 0;
        self::$_watcher = self::$_watcher / 3 > 1 ? self::$_watcher % 3 : self::$_watcher;

        self::$_deny = self::$_flag >= 5 ? 1 : 0;

        self::save();
        if (self::$_deny) {
            echo 'Inquisit\'d';
            die;
        }
    }

    public static function add_tmp()
    {
        self::init_required();
        self::$_tempo['time'] = time();
        self::$_tempo['flags'] = isset(self::$_tempo['flags']) ? self::$_tempo['flags'] + 1 : 1;
        sleep(1);
        self::compute();
    }

    public static function add_watcher()
    {
        self::init_required();
        self::$_watcher++;
        self::compute();
    }

    public static function add_flag()
    {
        self::init_required();
        self::$_flag++;
        self::compute();
    }

    public static function add_deny()
    {
        self::init_required();
        self::$_deny++;
        self::compute();
    }
}
