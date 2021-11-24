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
 * @copyright Copyright (c) 2016-2021, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

class mlText
{
    public $_keys = [];
    public $_textes = [];

    public function __construct($fnord = null)
    {
        if (!is_null($fnord)) {
            $this->import($fnord);
        }
    }

    public function add_text($k, $t)
    {
        $this->_textes[$k] = $t;
    }

    public function add_lang($k, $l)
    {
        $l = is_array($l) ? $l : [$l];
        if (isset($this->_keys[$k])) {
            foreach ($l as $key) {
                array_push($this->_keys[$k], $key);
            }
        } else {
            $this->_keys[$k] = $l;
        }
    }

    public function export()
    {
        $r = [];
        $r[0] = [];
        $r[1] = [];
        foreach ($this->_keys as $key => $value) {
            $r[0][$key] = $value;
        }
        foreach ($this->_textes as $key => $value) {
            $r[1][$key] = $value;
        }

        return json_encode($r);
    }

    public function import($fnord)
    {
        $fnord = json_decode($fnord);
        foreach ($fnord[0] as $key => $value) {
            $this->_keys[$key] = $value;
        }
        foreach ($fnord[1] as $key => $value) {
            $this->_textes[$key] = $value;
        }
    }

    public function get($lang = null)
    {
        $lang = is_null($lang) ? getLang() : $lang;
        $k = null;
        foreach ($this->_keys as $key => $value) {
            if (in_array($lang, $value)) {
                $k = $key;
            }
        }
        $k = is_null($k) ? 0 : $k;

        return $this->_textes[$k];
    }
}
