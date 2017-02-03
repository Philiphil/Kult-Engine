<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016-2017
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
 * @copyright Copyright (c) 2016-2017, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

class daoGenerator
{
    use queryable;
    public $_table;
    public $_obj;
    public $_id;
    private $_sql;

    public function __construct($fnord = null)
    {
        $this->_sql = new sqlHelper();
        if (is_string($fnord)) {
            $x = new \ReflectionClass($fnord);
            $x = $x->newInstance();
        } else {
            $x = $fnord;
            $fnord = get_class($fnord);
        }
        if (is_subclass_of($x, 'kult_engine\daoableObject')) {
            $this->_table = $x->db_table;
            $this->_obj = $x->db_obj;
            $this->_id = $x->db_id;
        }
    }

    public function get($fnord)
    {
        $query = $this->_sql->select_int($this->_table, $this->_id);
        $query = $this->query($query);
        $query->execute([$fnord]);
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        return isset($query[0]) ? unserialize($query[0][$this->_obj]) : false;
    }

    public function set($fnord)
    {
        $ser = serialize($fnord);
        if (intval($fnord->id) === 0) {
            $query = $this->_sql->insert($this->_table, $this->_obj);
            $query = $this->query($query);
            $query->execute([$ser]);
            $query = $this->_sql->select_string($this->_table, $this->_obj);
            $query = $this->query($query);
            $query->execute([$ser]);
            $query = $query->fetchAll(\PDO::FETCH_ASSOC);
            $id = $query[0][$this->_id];
            $fnord->id = intval($id);

            return $this->set($fnord);
        } else {
            $query = $this->_sql->update_int($this->_table, $this->_id, $this->_id, $this->_obj);
            $query = $this->query($query);
            $query->execute([$ser, $fnord->id]);

            return $fnord;
        }
    }

    public function get_last()
    {
        $query = $this->_sql->select_last($this->_table, $this->_id);
        $query = $this->query($query);
        $query->execute();
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        return isset($query[0]) ? unserialize($query[0][$this->_obj]) : false;
    }

    public function get_all()
    {
        $query = $this->_sql->select_all($this->_table, $this->_id);
        $query = $this->query($query);
        $query->execute();
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);
        $r = false;
        if (is_array($query) && count($query) > 0) {
            $r = [];
            foreach ($query as $key) {
                array_push($r, unserialize($key[$this->_obj]));
            }
        }

        return $r;
    }

    public function delete($fnord)
    {
        $query = $this->_sql->delete($this->_table, $this->_id);
        $query = $this->query($query);
        $query->execute([$fnord->id]);
    }

    public function create_table($size = 'MEDIUMBLOB')
    {
        $query = $this->_sql->create_basic($this->_table, $this->_id, $this->_obj, $size);
        $query = $this->query($query);
        $query->execute();
    }

    public function delete_table()
    {
        $query = $this->_sql->drop($this->_table);
        $query = $this->query($query);
        $query->execute();
    }

    public function empty_table()
    {
        $query = $this->_sql->truncate($this->_table);
        $query = $this->query($query);
        $query->execute();
    }
}
