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

class sqlHelper
{
    private function table_quoted($table)
    {
        return strpos($table, '`') === false ? '`'.$table.'`' : $table;
    }

    public function select_int($table, $cat, $id = 'id')
    {
        return 'SELECT * FROM '.$this->table_quoted($table).' WHERE '.$cat.' = :'.$id;
    }

    public function select_string($table, $cat, $id = 'id')
    {
        return 'SELECT * FROM '.$this->table_quoted($table).' WHERE '.$cat.' like :'.$id;
    }

    public function insert($table, $cat, $id = null)
    {
        $cat = is_array($cat) ? $cat : [$cat];
        $id = is_null($id) ? $cat : $id;
        $id = is_array($id) ? $id : [$id];

        $r = 'INSERT INTO '.$this->table_quoted($table).' ( `';

        for ($i = 0; $i < count($cat) - 1; $i++) {
            $r .= $cat[$i].'` , `';
        }
        $r .= $cat[count($cat) - 1].'` ) ';

        $r .= 'VALUES ( ';

        for ($i = 0; $i < count($id) - 1; $i++) {
            $r .= ':'.$id[$i].' , ';
        }
        $r .= ':'.$id[count($id) - 1].' ) ';

        return $r;
    }

    public function update_int($table, $haystack, $needle, $cat, $id = null)
    {
        $cat = is_array($cat) ? $cat : [$cat];
        $id = is_null($id) ? $cat : $id;
        $id = is_array($id) ? $id : [$id];

        $r = 'UPDATE '.$this->table_quoted($table);
        $r .= ' SET `';
        for ($i = 0; $i < count($cat) - 1; $i++) {
            $r .= $cat[$i].'` = :'.$id[$i].' , `';
        }
        $r .= $cat[count($cat) - 1].'` = :'.$id[count($cat) - 1];
        $r .= ' WHERE '.$this->table_quoted($haystack).' = :'.$needle;

        return $r;
    }

    public function update_string($table, $haystack, $needle, $cat, $id = null)
    {
        $cat = is_array($cat) ? $cat : [$cat];
        $id = is_null($id) ? $cat : $id;
        $id = is_array($id) ? $id : [$id];

        $r = 'UPDATE '.$this->table_quoted($table);
        $r .= ' SET `';
        for ($i = 0; $i < count($cat) - 1; $i++) {
            $r .= $cat[$i].'` = :'.$id[$i].' , `';
        }
        $r .= $cat[count($cat) - 1].'` = :'.$id[count($cat) - 1];
        $r .= ' WHERE '.$this->table_quoted($haystack).' like :'.$needle;

        return $r;
    }

    public function delete($table, $cat, $id = 'id')
    {
        return 'DELETE FROM '.$this->table_quoted($table).' WHERE '.$this->table_quoted($cat).' = :'.$id;
    }

    public function select_last($table, $cat)
    {
        return 'SELECT * FROM  '.$this->table_quoted($table).'WHERE 1=1 ORDER BY '.$cat.' DESC LIMIT 1';
    }

    public function select_all($table)
    {
        return 'SELECT * FROM  '.$this->table_quoted($table).'WHERE 1=1';
    }

    public function truncate($table)
    {
        return 'TRUNCATE TABLE '.$this->table_quoted($table);
    }

    public function drop($table)
    {
        return 'DROP TABLE '.$this->table_quoted($table);
    }

    public function create_basic($table, $id, $obj, $size = 'MEDIUMBLOB')
    {   //unused
        $v = 'CREATE TABLE '.$this->table_quoted($table);
        $v .= ' ( ';
        $v .= $id.' INT PRIMARY KEY AUTO_INCREMENT  NOT NULL,';
        $v .= $obj.' '.$size.')CHARSET=utf8mb4';

        return $v;
    }

    public function table_exists()
    {
        return "SHOW TABLES LIKE ':table'";
    }

    public function create_advance($table, $tableau)
    {
        $v = 'CREATE TABLE '.$this->table_quoted($table);
        $v .= ' ( ';
        $i = 0;
        foreach ($tableau as $key => $b) {
            $d = $i == 0 ? '' : ',';
            if ($b === 'id') {
                $v .= $d.$key.' INT PRIMARY KEY AUTO_INCREMENT NOT NULL';
            } elseif (is_array($b) || is_object($b)) {
                $v .= $d.$key.' LONGTEXT NULL';
            } elseif (gettype($b) === 'integer') {
                $v .= $d.$key." INT NOT NULL DEFAULT '0'";
            } elseif ($b === 'blob') {
                $v .= $d.$key.' MEDIUMBLOB NULL';
            } elseif (gettype($b) === 'string') {
                $v .= $d.$key.' TEXT NULL';
            } elseif (gettype($b) === 'double') {
                $v .= $d.$key." DOUBLE NOT NULL DEFAULT '0'";
            }
            $i++;
        }
        $v .= ')CHARSET=utf8mb4';

        return $v;
    }
}
