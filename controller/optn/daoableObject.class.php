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

abstract class daoableObject
{
    /*
        long ?
        id = "id"
        array = []
        obj=new obj()
     */
    public $_id = 'id';
    public $_iduniq = 'string';
    public $_tabletype = 0;

    public function __construct($typetable=0)
    {
        $this->_tabletype=$typetable;
        $this->setIduniq();
        foreach ($this as $key => $value) {
            if ($value == 'string') {
                $this->$key = '';
            }
        }
    }

    public function setIduniq()
    {
        $this->_iduniq = uniqid();
        return $this;
    }

    public function getDefaultId()
    {
        return 'id';
    }

    public function setDefaultId()
    {
        $this->_id = $this->getDefaultId();
        return $this;
    }

    public function clean()
    {
        unset($this->_iduniq);
        return $this;
    }

    public function clone()
    {
        $n = $this;
        $n->setIduniq()->setDefaultId();
        return $n;
    }
    public static $_ONE=0;
    public static $_ONETOMANY=1;
    public static $_MANYTOMANY=2;
    public static $_MANYTOONE=3;
}
