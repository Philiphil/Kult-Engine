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

class sanitizer
{
    public $_var = false;

    public function __invoke($fnord)
    {
        $this->_var = $fnord;

        return $this;
    }

    public function out()
    {
        return $this->_var;
    }

    public function int()
    {
        $this->_var = intval($this->_var);

        return $this;
    }

    public function pos()
    {
        $this->_var = $this->_var >= 0 ? $this->_var : false;

        return $this;
    }

    public function neg()
    {
        $this->_var = $this->_var < 0 ? $this->_var : false;

        return $this;
    }

    public function text()
    {
        $this->_var = htmlentities($this->_var);

        return $this;
    }

    public function mail()
    {
        $this->_var = filter_var($this->_var, FILTER_VALIDATE_EMAIL);

        return $this;
    }

    public function min($min)
    {
        $this->_var = $this->_var < $min ? false : $this->_var;

        return $this;
    }

    public function max($max)
    {
        $this->_var = $this->_var > $max ? false : $this->_var;

        return $this;
    }

    public function upper()
    {
        $this->_var = mb_strtoupper($this->_var, 'UTF-8');

        return $this;
    }

    public function lower()
    {
        $this->_var = mb_strtolower($this->_var, 'UTF-8');

        return $this;
    }

    public function upper_first()
    {
        $this->lower();
        $this->_var = ucfirst($this->_var);

        return $this;
    }

    public function upper_words()
    {
        $this->lower();
        $this->_var = ucwords($this->_var);

        return $this;
    }

    public function min_length($min)
    {
        $this->_var = mb_strlen($this->_var, 'UTF-8') < $min ? false : $this->_var;

        return $this;
    }

    public function max_length($max)
    {
        $this->_var = mb_strlen($this->_var, 'UTF-8') > $max ? false : $this->_var;

        return $this;
    }

    public function not_null()
    {
        $this->_var = $this->_var == '' || is_null($this->_var) ? false : $this->_var;

        return $this;
    }
}
