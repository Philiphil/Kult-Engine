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

use KultEngine\Core\Dao\Helper\SQLHelper;

class DaoGeneratorSQL extends BaseDaoGenerator
{
    public string $_dateTimeFormat = 'Y-m-d H:i:s';

    public function __construct(DaoableObject $fnord, AbstractConnector $connector)
    {
        $this->asign($fnord);
        $this->setConnector($connector);
        $this->_helper = new SQLHelper();
    }

    public function insert(DaoableObject $fnord): DaoableObject
    {
        $this->verifyTable();

        list($daoableProperties, $classname) = $this->objToDaoableProperties($fnord);

        foreach ($daoableProperties as $daoableProperty) {
            if ($daoableProperty->persist &&
                ($daoableProperty->value == $fnord->__getDefaultId() ||
                !$daoableProperty->isNullable && $daoableProperty->value === null)
            ) {
                $fnord->{$daoableProperty->name} = $this->insert($fnord->{$daoableProperty->name});
            }
        }/**/
        $o = $this->objToRow($fnord, false);
        if ($fnord->__id->value === $fnord->__getDefaultId()) {
            $query = $this->_helper->insert($classname, $o[0]);
            var_dump($query);
            var_dump($o[1]);
            $query = $this->query($query);
            var_dump($query);
            var_dump($o[1]);
            $query->execute($o[1]);
            $query = $this->_helper->select_string($classname, '__iduniq');
            $query = $this->query($query);
            $query->execute([$fnord->__iduniq]);
            $query = $query->fetchAll(\PDO::FETCH_ASSOC);
            $fnord->__id->value = $query[0]['__id'];
            return $fnord;
        } else {
            $query = $this->_helper->update_int($classname, '__id', '__id', $o[0]);
            $query = $this->query($query);
            $o[1][] = $fnord->__id->value;
            $query->execute($o[1]);
        }

        return $fnord;
    }

    public function selectLast(): ?DaoableObject
    {
        $this->verifyTable();
        $query = $this->_helper->select_last($this->_classname, '__id');
        $query = $this->query($query);
        $query->execute();
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        return isset($query[0]) ? $this->rowToObj($query[0]) : null;
    }

    public function selectAll()
    {
        $this->verifyTable();
        $query = $this->_helper->select_all($this->_classname, '__id');
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

    public function delete(DaoableObject $fnord)
    {
        $this->verifyTable();
        $query = $this->_helper->delete($this->_classname, '__id');
        $query = $this->query($query);
        $query->execute([$fnord->__id]);
    }

    public function createTable()
    {
        if ($this->tableExists()) {
            return;
        }
        $x = $this->_obj;
        $query = $this->_helper->create_advance($this->_classname, $x);
        $query = $this->query($query);
        $query->execute();
    }

    public function deleteTable()
    {
        $this->verifyTable();
        $query = $this->_helper->drop($this->_classname);
        $query = $this->query($query);
        $query->execute();
    }

    public function emptyTable()
    {
        $this->verifyTable();
        $query = $this->_helper->truncate($this->_classname);
        $query = $this->query($query);
        $query->execute();
    }

    public function select($val, string $key = '__id', bool $multi = false)
    {
        $this->verifyTable();

        return $this->_select($val, $this->_classname, $this->_obj, $key, $multi);
    }

    public function _select($val, string $classname, array $obj, string $key = '__id', bool $multi = false)
    {
        $query = (gettype($obj[$key]) === 'integer' ||
            $obj[$key] === 'id' || gettype($obj[$key]) === 'boolean' ||
            gettype($obj[$key]) === 'double')
            ? $this->_helper->select_int($classname, $key, $key)
            : $this->_helper->select_string($classname, $key, $key);
        $query = $this->query($query);
        $query->execute([$val]);
        $query = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($query) == 0) {
            return 0;
        }
        if (!$multi && count($query) > 1) {
            return false;
        }
        if (!$multi) {
            return $this->rowToObj($query[0], $classname);
        }
        if ($multi) {
            $r = [];
            foreach ($query as $key) {
                $r[] = $this->rowToObj($key[0], $classname);
            }

            return $r;
        }
    }

    public function tableExists(): bool
    {
        try {
            $query = $this->_helper->select_last($this->_classname, '__id');
            $query = $this->query($query);
            $query->execute();
            $query = $query->fetchAll(\PDO::FETCH_ASSOC);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
