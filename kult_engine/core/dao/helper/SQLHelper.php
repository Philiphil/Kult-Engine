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
use KultEngine\Core\Dao\DaoableProperty;

class SQLHelper
{
    private function table_quoted($table): string
    {
        return strpos($table, '`') === false ? '`'.$table.'`' : $table;
    }

    public function select_int($table, $cat, $id = 'id'): string
    {
        return 'SELECT * FROM '.$this->table_quoted($table).' WHERE '.$cat.' = :'.$id;
    }

    public function select_string($table, $cat, $id = 'id'): string
    {
        return 'SELECT * FROM '.$this->table_quoted($table).' WHERE '.$cat.' like :'.$id;
    }

    public function insert($table, $cat, $id = null): string
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

    public function update_int($table, $haystack, $needle, $cat, $id = null): string
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

    public function update_string($table, $haystack, $needle, $cat, $id = null): string
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

    public function delete($table, $cat, $id = 'id'): string
    {
        return 'DELETE FROM '.$this->table_quoted($table).' WHERE '.$this->table_quoted($cat).' = :'.$id;
    }

    public function select_last($table, $cat): string
    {
        return 'SELECT * FROM  '.$this->table_quoted($table).'WHERE 1=1 ORDER BY '.$cat.' DESC LIMIT 1';
    }

    public function select_all($table): string
    {
        return 'SELECT * FROM  '.$this->table_quoted($table).'WHERE 1=1';
    }

    public function truncate($table): string
    {
        return 'TRUNCATE TABLE '.$this->table_quoted($table);
    }

    public function drop($table): string
    {
        return 'DROP TABLE '.$this->table_quoted($table);
    }

    public function table_exists(): string
    {
        return "SHOW TABLES LIKE ':table'";
    }

    public function create_advance($table, $tableau): string
    {
        $v = 'CREATE TABLE '.$this->table_quoted($table);
        $v .= ' ( ';
        $i = 0;
        foreach ($tableau as $key => $b) {
            $d = $i == 0 ? '' : ',';

            $str = $d.$key;
            switch ($b->type) {
                case DaoableProperty::TYPE_ID:
                    $str .= " INT PRIMARY KEY AUTO_INCREMENT";
                    break;
                case DaoableProperty::TYPE_DATETIME:
                    $str .= " datetime";
                    break;
                case DaoableProperty::TYPE_INT:
                    $str .= " INT";
                    break;
                case DaoableProperty::TYPE_STRING:
                    $str .= " TEXT";
                    break;
                case DaoableProperty::TYPE_DOUBLE:
                    $str .= " DOUBLE";
                    break;
                case DaoableProperty::TYPE_BOOL:
                    $str .= " BOOLEAN";
                    break;
                case DaoableProperty::TYPE_BLOB:
                    $str .= " MEDIUMBLOB";
                    break;
                default:
                case DaoableProperty::TYPE_LONGTEXT:
                    $str .= " LONGTEXT";
                    break;
            }
            $str .= $b->isNullable ? " NULL" : " NOT NULL";
            if($b->defaultValue !== null) $str .= " DEFAULT '".addslashes($b->defaultValue)."'";
            $v .= $str;

            $i++;
        }
        $v .= ')CHARSET=utf8mb4';

        return $v;
    }
}
