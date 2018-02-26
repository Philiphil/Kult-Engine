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

abstract class text
{
    use coreElement;
    public static $_server;
    public static $_default;

    public static function setter()
    {
        self::$_default = constant('default_lang');
        self::$_server = constant('server_lang');
    }

    public static function setter_conf($fnord)
    {
        self::$_server = $fnord['server'];
        self::$_default = $fnord['default'];
    }

    public static function get_lang()
    {
        $user = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $bfr = [];
        $i = 0;
        foreach ($user as $key) {
            $i++;
            $bfr[$i] = $key;
            if (strpos($key, ';') != false) {
                $bfr[$i] = substr($key, 0, strpos($key, ';'));
            }
        }
        $choice = array_merge(array_intersect($bfr, self::$_server));
        $result = '';
        if (count($choice) == 0) {
            foreach ($bfr as $key => $value) {
                $bfr[$key] = substr($value, 0, 2);
            }
            $choice = array_merge(array_intersect($bfr, self::$_server));
            if (count($choice) == 0) {
                $result = self::$_default;
            } else {
                $result = $choice[0];
            }
        } else {
            $result = $choice[0];
        }

        return $result;
    }

    public static function get_true_lang()
    {
        return explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];
    }

    public static function get_text($texte, $lang = null)
    {
        $lang = is_null($lang) ? self::get_lang() : $lang;
        $array = texts();
        $r = isset($array[$lang][$texte]) ? $array[$lang][$texte] : null;
        if (is_null($r)) {
            if (isset($array[self::$_default][$texte])) {
                $r = $array[self::$_default][$texte];
            } else {
                $r = $texte;
            }
        }

        return $r;
    }
}
