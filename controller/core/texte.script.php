<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016
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
 * @copyright Copyright (c) 2016, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

/*
    Centralisation des textes
*/

    function get_lang()
    {
        $server = ['fr', 'en'];
        $default = 'en';

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
        $choice = array_merge(array_intersect($bfr, $server));
        $result = '';
        if (count($choice) == 0) {
            $result = $default;
        } else {
            $result = $choice[0];
        }

        return $result;
    }





    function get_text($texte, $lang=null)
    {
        $lang = is_null($lang) ? get_lang() : $lang;
        $array = textes();
        return isset($array[$lang][$texte]) ? $array[$lang][$texte] : $texte;
    }
