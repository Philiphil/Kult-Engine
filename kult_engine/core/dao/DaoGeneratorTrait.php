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
 * @copyright Copyright (c) 2016-2020, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

use KultEngine\Core\Dao\DaoableProperty;
use KultEngine\Core\Dao\Id;

trait DaoGeneratorTrait
{
    public string $_classname = '';
    public array $_obj = [];
    public $_helper = null;
    public AbstractConnector $_connector;
    public string $_dateTimeFormat = 'Y-m-d H:i:s';

    public function setConnector(AbstractConnector $fnord)
    {
        $this->_connector = $fnord;
    }

    public function query($fnord)
    {
        return $this->_connector::query($fnord);
    }

    public function asign(DaoableObject $fnord)
    {
        $this->_obj = [];
        $x = new \ReflectionClass($fnord);
        $this->_classname = $x->getName();
        $this->_classname = strpos($this->_classname, 'KultEngine\\') === 0 ? substr($this->_classname, 11) : $this->_classname;
        $properties = $x->getProperties();
        $instance = $x->newInstanceWithoutConstructor();
        foreach ($properties as $p) {
            $daoP = new DaoableProperty();
            $daoP->name = $p->getName();
            $daoP->setType($p->getType()->getName());
            $daoP->isNullable = $p->getType()->allowsNull();
            $daoP->defaultValue = isset($instance->{$p->getName()}) ? $instance->{$p->getName()} : null;

            $this->_obj[$daoP->name] = $daoP;
        }
    }

    public function objToRow(DaoableObject $o, bool $keepId = true)
    {
        $x = new \ReflectionClass($o);
        $properties = $x->getProperties();
        $r = [];
        $i = 0;
        foreach ($properties as $p) {
            if ($keepId || (!$keepId && $p->getName() != '__id')) {
                $r[0][$i] = $p->getName();

                if (!isset($o->{$p->getName()})) {
                    $r[1][$i] = null;
                } elseif (is_object($o->{$p->getName()}) && is_subclass_of($o->{$p->getName()}, DaoableProperty::class)) {
                    $r[1][$i] = $o->{$p->getName()}->value;
                } elseif (is_object($o->{$p->getName()}) && get_class($o->{$p->getName()}) == \DateTime::class) {
                    $r[1][$i] = $o->{$p->getName()}->format($this->_dateTimeFormat);
                } else {
                    $r[1][$i] = is_array($o->{$p->getName()}) || is_object($o->{$p->getName()}) ? serialize($o->{$p->getName()}) : $o->{$p->getName()};
                }
                $i++;
            }
        }

        return $r;
    }

    public function rowToObj($r)
    {
        $x = new \ReflectionClass($this->_classname);
        $o = $x->newInstance();
        foreach ($r as $key => $value) {
            if ($this->_obj[$key]->isPhpType()) {
                $o->{$key} = $value;
            } else {
                $o->{$key} = $this->getDaoPropertyInstance(
                    $this->_obj[$key],
                    $value
                );
            }
        }

        return $o;
    }

    public function getDaoPropertyInstance($property, $value)
    {
        switch ($property->type) {
            case DaoableProperty::TYPE_DATETIME:
                return new \DateTime($value);
            case DaoableProperty::TYPE_ID:
                $that = new Id();
                $that->value = $value;

                return $that;
            default:
                return null;
        }
    }

    public function verify_table()
    {
        if (!$this->table_exists()) {
            $this->create_table();
        }
    }
}
