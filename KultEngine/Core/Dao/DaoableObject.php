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
 * @copyright Copyright (c) 2016-2222, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine\Core\Dao;

abstract class DaoableObject
{
    public Id $__id;
    public string $__iduniq;

    public function __construct()
    {
        $this->__setDefaultId();
        $this->__setIduniq();
    }

    public function __setIduniq(?string $uniq = null): daoableObject
    {
        $this->__iduniq = $uniq ?? realUniqid();

        return $this;
    }

    public function __getDefaultId(): int
    {
        return -1;
    }

    public function __setDefaultId(): daoableObject
    {
        $this->__id = new Id();
        $this->__id->value = $this->__getDefaultId();

        return $this;
    }

    public function __clean(): daoableObject
    {
        unset($this->__iduniq);

        return $this;
    }

    public function __clone()
    {
        $n = $this;
        $n->__setIduniq()->__setDefaultId();

        return $n;
    }

    public function __set($name, $value)
    {
        if (!isset(self::$name)) {
            return;
        }
    }

    public function __get($name)
    {
        if (!isset(self::$name)) {
            return;
        }
    }
}
