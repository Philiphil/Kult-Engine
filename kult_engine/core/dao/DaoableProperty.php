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

class DaoableProperty
{
    public int $type = 1;
    public bool $isNullable = false;
    public $defaultValue = null;
    public string $name = '';
    public $value;

    const TYPE_ID = 1;
    const TYPE_DATETIME = 2;
    const TYPE_FOREIGN_KEY = 3;
    const TYPE_INT = 4;
    const TYPE_STRING = 5;
    const TYPE_DOUBLE = 6;
    const TYPE_BOOL = 7;
    const TYPE_SERIAL = 8;
    const TYPE_LONGTEXT = 9;
    const TYPE_BLOB = 10;


    const TYPE_ONE_TO_ONE_RELATION = 11;
    const TYPE_MANY_TO_ONE_RELATION = 12;
    const TYPE_ONE_TO_MANY_RELATION = 13;
    const TYPE_MANY_TO_MANY_RELATION = 14;

    const REGULAR_TYPES = [
        self::TYPE_INT,
        self::TYPE_STRING,
        self::TYPE_DOUBLE,
        self::TYPE_BOOL,

    ];

    public function setType(string $nameType)
    {
        $this->type = $this->getTypeConst($nameType);
    }

    public function getTypeConst(string $nameType)
    {
        switch ($nameType) {
            case 'KultEngine\Core\Dao\Id':
                return self::TYPE_ID;
            case 'DateTime':
            case '\DateTime':
                return self::TYPE_DATETIME;
            case 'int':
                return self::TYPE_INT;
            case 'string':
                return self::TYPE_STRING;
            case 'double':
            case 'real':
            case 'float':
                return self::TYPE_DOUBLE;
            case 'bool':
                return self::TYPE_BOOL;
            case 'KultEngine\OneToOneRelation':
                return self::TYPE_ONE_TO_ONE_RELATION;
            case 'KultEngine\ManyToOneRelation':
                return self::TYPE_MANY_TO_ONE_RELATION;
            case 'KultEngine\OneToManyRelation':
                return self::TYPE_ONE_TO_MANY_RELATION;
            case 'KultEngine\ManyToManyRelation':
                return self::TYPE_MANY_TO_MANY_RELATION;
            default:
                return self::TYPE_SERIAL;
        }
    }

    public function isPhpType()
    {
        return in_array($this->type, self::REGULAR_TYPES);
    }
}
