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

class daoGeneratorSQL extends daoGenerator
{
    public function __construct($fnord = null)
    {
        parent::__construct($fnord);
        $this->_helper = new sqlHelper();
    }

    public function __invoke($fnord)
    {
        $bfr = new self($fnord);
        $this->_obj = $bfr->_obj;
    }

    public function set($fnord)
    {
        $this->verify_table();
        if ($fnord->_id === $fnord->getDefaultId()) {
            $o = $this->objToRow($fnord, 0);
            $query = $this->_helper->insert($this->_obj[0], $o[0]);
            $query = $this->query($query);
            $query->execute($o[1]);
            $query = $this->_helper->select_string($this->_obj[0], '_iduniq');
            $query = $this->query($query);
            $query->execute([$fnord->_iduniq]);
            $query = $query->fetchAll(\PDO::FETCH_ASSOC);
            $fnord->_id = $query[0]['_id'];

            return $fnord;
        } else {
            $o = $this->objToRow($fnord, 0);
            $query = $this->_helper->update_int($this->_obj[0], '_id', '_id', $o[0]);
            $query = $this->query($query);
            $o[1][] = $fnord->_id;
            $query->execute($o[1]);

            return $fnord;
        }
    }

    public function get_last()
    {
        $this->verify_table();
        $query = $this->_helper->select_last($this->_obj[0], '_id');
        $query = $this->query($query);
        $query->execute();
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        return isset($query[0]) ? $this->rowToObj($query[0]) : false;
    }

    public function get_all()
    {
        $this->verify_table();
        $query = $this->_helper->select_all($this->_obj[0], '_id');
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
        $query = $this->_helper->delete($this->_obj[0], '_id');
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
        $query = $this->_helper->create_advance($this->_obj[0], $x);
        $query = $this->query($query);
        $query->execute();
    }

    public function delete_table()
    {
        $this->verify_table();
        $query = $this->_helper->drop($this->_obj[0]);
        $query = $this->query($query);
        $query->execute();
    }

    public function empty_table()
    {
        $this->verify_table();
        $query = $this->_helper->truncate($this->_obj[0]);
        $query = $this->query($query);
        $query->execute();
    }

    public function select($val, $wat = '_id', $mult = 0)
    {
        $this->verify_table();
        $query = $this->_obj[$wat] === 0 || $this->_obj[$wat] === 'id' || $this->_obj[$wat] === 0.0 ? $this->_helper->select_int($this->_obj[0], $wat, $wat) : $this->_helper->select_string($this->_obj[0], $wat, $wat);
        $query = $this->query($query);
        $query->execute([$val]);
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($query) == 0) {
            return 0;
        }
        if (!$mult && count($query) > 1) {
            return false;
        }
        if (!$mult) {
            return $this->rowToObj($query[0]);
        }
        if ($mult) {
            $r = [];
            foreach ($query as $key) {
                $r[] = $this->rowToObj($key[0]);
            }

            return $r;
        }
    }

    public function table_exists()
    {
        try {
            $query = $this->_helper->select_last($this->_obj[0], '_id');
            $query = $this->query($query);
            $query->execute();
            $query = $query->fetchAll(\PDO::FETCH_ASSOC);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
