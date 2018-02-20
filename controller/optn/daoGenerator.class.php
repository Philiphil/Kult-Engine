<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016-208
 *
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

class daoGenerator
{
    use queryable;
    public $_obj;
    private $_sql;

    public function __construct($fnord = null)
    {
        $this->_sql = new sqlHelper();
        $x = new \ReflectionClass($fnord);
        $this->_obj[0] = $x->getName();
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

    public function get($fnord)
    {
        $this->verify_table();
        $query = $this->_sql->select_string($this->_obj[0], '_id');
        $query = $this->query($query);
        $query->execute([$fnord]);
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        return isset($query[0]) ? $this->rowToObj($query[0]) : false;
    }

    public function set($fnord)
    {
        $this->verify_table();
        if ($fnord->_id === 'id') {
            $o = $this->objToRow($fnord, 0);
            $query = $this->_sql->insert($this->_obj[0], $o[0]);
            $query = $this->query($query);
            $query->execute($o[1]);
            $query = $this->_sql->select_string($this->_obj[0], '_iduniq');
            $query = $this->query($query);
            $query->execute([$fnord->_iduniq]);
            $query = $query->fetchAll(\PDO::FETCH_ASSOC);
            $fnord->_id = $query[0]['_id'];

            return $fnord;
        } else {
            $o = $this->objToRow($fnord, 0);
            $query = $this->_sql->update_int($this->_obj[0], '_id', '_id', $o[0]);
            $query = $this->query($query);
            $o[1][] = $fnord->_id;
            $query->execute($o[1]);

            return $fnord;
        }
    }

    public function get_last()
    {
        $this->verify_table();
        $query = $this->_sql->select_last($this->_obj[0], '_id');
        $query = $this->query($query);
        $query->execute();
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        return isset($query[0]) ? $this->rowToObj($query[0]) : false;
    }

    public function get_all()
    {
        $this->verify_table();
        $query = $this->_sql->select_all($this->_obj[0], '_id');
        $query = $this->query($query);
        $query->execute();
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);
        $r = false;
        if (is_array($query) && count($query) > 0) {
            $r = [];
            foreach ($query as $key) {
                array_push($r, $this->rowToObj($key));
            }
        }

        return $r;
    }

    public function delete($fnord)
    {
        $this->verify_table();
        $query = $this->_sql->delete($this->_obj[0], '_id');
        $query = $this->query($query);
        $query->execute([$fnord->_id]);
    }

    public function create_table()
    {
        if ($this->table_exists()) {
            return;
        }
        $x = $this->_obj;
        unset($x[0]);
        $query = $this->_sql->create_advance($this->_obj[0], $x);
        $query = $this->query($query);
        $query->execute();
    }

    public function delete_table()
    {
        $this->verify_table();
        $query = $this->_sql->drop($this->_obj[0]);
        $query = $this->query($query);
        $query->execute();
    }

    public function empty_table()
    {
        $this->verify_table();
        $query = $this->_sql->truncate($this->_obj[0]);
        $query = $this->query($query);
        $query->execute();
    }

    public function select($val, $wat = '_id', $mult = 0)
    {
        $this->verify_table();
        $r = [];
        $array = $this->get_all();
        foreach ($array as $o) {
            if ($o->$wat == $val) {
                array_push($r, $o);
            }
        }
        if (count($r) == 0) {
            return 0;
        }
        if (!$mult && count($r) > 1) {
            return false;
        }
        if (!$mult) {
            return $r[0];
        }
        if ($mult) {
            return $r;
        }
    }

    public function select_all($val, $wat)
    {
        return $this->select($val, $wat, 1);
    }

    public function table_exists()
    {
        try {
            $query = $this->_sql->select_last($this->_obj[0], '_id');
            $query = $this->query($query);
            $query->execute();
            $query = $query->fetchAll(\PDO::FETCH_ASSOC);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verify_table()
    {
        if (!$this->table_exists()) {
            $this->create_table();
        }
    }
}
