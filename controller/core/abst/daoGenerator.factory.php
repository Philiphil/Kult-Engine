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

abstract class daoGeneratorFactory
{
    public $_obj;
    public $_helper;
    public $_connector;
    use queryable; //queryable's last days

    public function setConnector($fnord)
    {
    }

    public function __construct($fnord = null)
    {
        $x = new \ReflectionClass($fnord);
        $this->_obj[0] = $x->getName();
        $this->_obj[0] = strpos($this->_obj[0], 'kult_engine\\') === 0 ? substr($this->_obj[0], 12) : $this->_obj[0];
        $b = $x->getProperties();
        $o = $x->newInstanceWithoutConstructor();
        foreach ($b as $p) {
            $this->_obj[$p->getName()] = $o->{$p->getName()};
        }
    }

    public function objToRow($o, $id = 1)
    {
        $x = new \ReflectionClass($o);
        $a = $x->newInstanceWithoutConstructor();
        $b = $x->getProperties();
        $r = [];
        $i = 0;
        foreach ($b as $p) {
            if ($id == 1 || ($id == 0 && $p->getName() != '_id')) {
                $r[0][$i] = $p->getName();
                $r[1][$i] = is_array($o->{$p->getName()}) || is_object($o->{$p->getName()}) ? serialize($o->{$p->getName()}) : $o->{$p->getName()};
                $i++;
            }
        }

        return $r;
    }

    public function rowToObj($r)
    {
        $x = new \ReflectionClass($this->_obj[0]);
        $a = $x->newInstanceWithoutConstructor();
        $o = $x->newInstance();
        foreach ($r as $key => $value) {
            $o->{$key} = is_array($a->{$key}) || is_object($a->{$key}) ? unserialize($value) : $value;
        }

        return $o;
    }

    public function __invoke($fnord)
    {
        $bfr = new self($fnord);
        $this->_obj = $bfr->_obj;
    }

    public function set($fnord)
    {
    }

    public function get_last()
    {
    }

    public function get_all()
    {
    }

    public function delete($fnord)
    {
    }

    public function create_table()
    {
        if ($this->table_exists()) {
            return;
        }
    }

    public function delete_table()
    {
    }

    public function empty_table()
    {
    }

    public function select($val, $wat = '_id', $mult = 0)
    {
    }

    public function select_all($val, $wat)
    {
        return $this->select($val, $wat, 1);
    }

    public function table_exists()
    {
    }

    public function verify_table()
    {
        if (!$this->table_exists()) {
            $this->create_table();
        }
    }
}
