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

namespace KultEngine\Core\Dao;

use KultEngine\Core\Dao\Relation\Cascade;
use KultEngine\Core\Dao\Relation\Relation;

abstract class BaseDaoGenerator
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
        list($this->_obj, $this->_classname) = $this->objToDaoableProperties($fnord);
    }

    public function objToDaoableProperties(DaoableObject $fnord): array
    {
        $_obj = [];
        $x = new \ReflectionClass($fnord);
        $_classname = $x->getName();
        $_classname = strpos($_classname, 'KultEngine\\') === 0 ? substr($_classname, 11) : $_classname;
        $properties = $x->getProperties();
        $instance = $x->newInstanceWithoutConstructor();
        foreach ($properties as $p) {
            $daoP = new DaoableProperty();
            $daoP->name = $p->getName();
            $daoP->setType($p->getType()->getName());

            foreach ($p->getAttributes() as $attribute) {
                switch ($attribute->getName()) {
                    case "KultEngine\Core\Dao\Relation\OneToOne":
                    case "KultEngine\Core\Dao\Relation\ManyToOne":
                    //case "KultEngine\Core\Dao\Relation\ManyToMany"://liaison table then
                    case "KultEngine\Core\Dao\Relation\OneToMany":
                        $daoP->class = $p->getType()->getName();
                        $daoP->setType($attribute->getName());
                        $daoP->persist = in_array(Cascade::PERSIST, $attribute->getArguments());
                        $daoP->remove = in_array(Cascade::REMOVE, $attribute->getArguments());
                        break;
                }
            }
            $daoP->isNullable = $p->getType()->allowsNull();
            $daoP->defaultValue = $instance->{$p->getName()} ?? null;

            $_obj[$daoP->name] = $daoP;
        }

        return [$_obj, $_classname];
    }

    public function objToRow(DaoableObject $o, bool $keepId = true): array
    {
        $r = [];
        $i = 0;

        if ($o::class != $this->_classname) {
            list($obj, $classname) = $this->objToDaoableProperties($o);
        } else {
            $obj = $this->_obj;
        }

        foreach ($obj as $p) {
            if ($keepId || $p->name != '__id') {
                $r[0][$i] = $p->name;

                if (!isset($o->{$p->name})) {
                    $r[1][$i] = null;
                } elseif (is_object($o->{$p->name}) && is_subclass_of($o->{$p->name}, DaoableProperty::class)) {
                    $r[1][$i] = $o->{$p->name}->value;
                } elseif (is_object($o->{$p->name}) && get_class($o->{$p->name}) == \DateTime::class) {
                    $r[1][$i] = $o->{$p->name}->format($this->_dateTimeFormat);
                } else {
                    /*is unknown object, serialize or relation*/
                    switch ($p->type) {
                        case DaoableProperty::TYPE_ONE_TO_ONE_RELATION:
                        case DaoableProperty::TYPE_MANY_TO_ONE_RELATION:
                            $r[1][$i] = $o->{$p->name}->__id->value;
                            break;
                        default:
                            $r[1][$i] = is_array($o->{$p->name}) || is_object($o->{$p->name}) ? serialize($o->{$p->name}) : $o->{$p->name};
                            break;
                    }
                }
                $i++;
            }
        }

        return $r;
    }

    public function rowToObj($r, $classname = null): DaoableObject
    {
        $x = new \ReflectionClass($classname ?? $this->_classname);
        $o = $x->newInstance();
        if ($classname && $classname != $this->_classname) {
            list($obj, $classname) = $this->objToDaoableProperties($o);
        } else {
            $obj = $this->_obj;
        }
        foreach ($r as $key => $value) {
            if ($obj[$key]->isPhpType()) {
                $o->{$key} = $value;
            } else {
                $o->{$key} = $this->getDaoPropertyInstance(
                    $obj[$key],
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
            case DaoableProperty::TYPE_ONE_TO_ONE_RELATION:
            case DaoableProperty::TYPE_MANY_TO_ONE_RELATION:
            case DaoableProperty::TYPE_ONE_TO_MANY_RELATION:
            case DaoableProperty::TYPE_MANY_TO_MANY_RELATION:
                list($_obj, $_classname) = $this->objToDaoableProperties(new $property->classtype());
                $object = static::_select($value, $_classname, $_obj);

                return new $property->classtype(); //$object;
            default:
                return null;
        }
    }

    public function verifyTable(): void
    {
        $this->_verifyTable($this->_classname);
    }

    public function _verifyTable(string $classname): void
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }
    }

    abstract public function insert(DaoableObject $fnord);

    abstract public function selectLast();

    abstract public function selectAll();

    abstract public function delete(DaoableObject $fnord);

    abstract public function createTable();

    abstract public function deleteTable();

    abstract public function emptyTable();

    abstract public function _select($val, string $classname, array $obj, string $key = '__id', bool $multi = false);

    abstract public function select($val, string $key, bool $multi);

    abstract public function tableExists();

}
