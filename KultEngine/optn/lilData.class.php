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

class lilData implements \ArrayAccess
{
    public $_datas = [];
    public $_name = '';

    public function __construct($n = 'main')
    {
        $this->_name = $n;
        if ($t = unserialize(file_get_contents(constant('tmppath').$this->_name.'.lil'))) {
            $this->_data = $t->_data;
        }
    }

    public function offsetSet($offset, $value)
    {
        $this->_datas[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->_datas[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->_datas[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->_datas[$offset];
    }

    public function save()
    {
        file_put_contents(constant('tmppath').$this->_name.'.lil', serialize($this));
    }
}
