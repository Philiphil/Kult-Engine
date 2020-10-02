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
use KultEngine\Core\Dao\Id;


class Relation extends DaoableObject{
    const TYPE_MANY_TO_MANY=1;
    const TYPE_MANY_TO_ONE=2;
    const TYPE_ONE_TO_MANY=3;
    const TYPE_ONE_TO_ONE=4;
    public int $type=0;

    public KultEngine\DaoableObject $source;
    public KultEngine\DaoableObject $target;

    public ?string $sourcTable;
    public ?string $targetTable;
    public ?string $sourceColumn;
    public ?string $targetColumn;

    public function __construct(int $type, KultEngine\DaoableObject $target){
        $this->type =$type;
        $this->target=$target;
        parent::construct();
    }

}

abstract class DaoableObject
{
    /*
        long ?
        id = "id"
        array = []
        obj=new obj()
     */
    public Id $__id;
    public string $__iduniq ;

    public function __construct()
    {
        $this->setDefaultId();
        $this->setIduniq();
    }

    public function setIduniq(): daoableObject
    {
        $this->_iduniq = uniqid();

        return $this;
    }

    public function getDefaultId(): int
    {
        return -1;
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
}
