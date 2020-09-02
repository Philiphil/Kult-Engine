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

class DaoGenerator
{
    protected $_obj = null;
    protected $_helper = null;
    protected AbstractConnector $_connector;
    private $_daughter = null;

    protected function setConnector(AbstractConnector $fnord)
    {
        $this->_connector = $fnord;
    }

    protected function query($fnord)
    {
        return $this->_connector::query($fnord);
    }

    public function __construct($fnord = null, AbstractConnector $connector = null)
    {
        switch ($connector::getDriver()) {
            case 'mysql':
                $this->_daughter = new DaoGeneratorSQL($fnord, $connector);
                break;
        }
    }

    public function __call($fn, $params)
    {
        return $this->_daughter->$fn(...$params);
    }

    protected function asign($fnord)
    {
        $this->_obj = [];
        $x = new \ReflectionClass($fnord);
        $this->_obj[0] = $x->getName();
        $this->_obj[0] = strpos($this->_obj[0], 'kult_engine\\') === 0 ? substr($this->_obj[0], 12) : $this->_obj[0];
        $b = $x->getProperties();
        $o = $x->newInstanceWithoutConstructor();
        foreach ($b as $p) {
            //old 
            if(isset($o->{$p->getName()}) ){
                $this->_obj[$p->getName()] = $o->{$p->getName()};
            }else{                
                $this->_obj[$p->getName()] = $p->getType();
            }
        }
    }

    protected function objToRow($o, $id = 1)
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

    protected function rowToObj($r)
    {
        $x = new \ReflectionClass($this->_obj[0]);
        $a = $x->newInstanceWithoutConstructor();
        $o = $x->newInstance();
        foreach ($r as $key => $value) {
            @$o->{$key} = is_array($a->{$key}) || is_object($a->{$key}) ? unserialize($value) : $value;
        }

        return $o;
    }

    public function __invoke($fnord)
    {
        $this->asign($fnord);
    }

    protected function set($fnord)
    {
    }

    protected function get_last()
    {
    }

    protected function get_all()
    {
    }

    protected function delete($fnord)
    {
    }

    protected function create_table()
    {
        if ($this->table_exists()) {
            return;
        }
    }

    protected function delete_table()
    {
    }

    protected function empty_table()
    {
    }

    protected function select($val, string $wat = '_id', bool $mult = false)
    {
    }

    protected function select_all($val, string $wat)
    {
        return $this->select($val, $wat, true);
    }

    protected function table_exists()
    {
    }

    protected function verify_table()
    {
        if (!$this->table_exists()) {
            $this->create_table();
        }
    }
}
