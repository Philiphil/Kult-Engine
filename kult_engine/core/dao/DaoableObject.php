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

trait daoablePropertyTypeTrait{
    abstract public function getValue();
    abstract public function getType();
}

class id{
    use daoablePropertyTypeTrait;
    public int $id=0;
    public function getValue(){ return $id;}
    public function getType(){ return "int";}
}
class uniqid{
    use daoablePropertyTypeTrait;
    public string $uniqid="";
    public function getValue(){ return $uniqid;}
    public function getType(){ return "string";}
}

abstract class daoableObject
{
    /*
        long ?
        id = "id"
        array = []
        obj=new obj()
     */

    protected id $_id;
    protected uniqid $_iduniq;

    public function __construct()
    {
        $this->id=new id();
        $this->uniqid=new uniqid();

        $this->setIduniq();
    }

    public function setIduniq(): daoableObject
    {
        $this->_iduniq=new uniqid();
        $this->_iduniq->uniqid = uniqid();

        return $this;
    }

    public function getDefaultId(): id
    {
        return new id();
    }

    public function setDefaultId(): daoableObject
    {
        $this->_id = $this->getDefaultId();

        return $this;
    }

    public function clean(): daoableObject
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

    public function __get($name)
    {
        if()
    }

    public function __set($name,$value)
    {
        if()
    }
}
