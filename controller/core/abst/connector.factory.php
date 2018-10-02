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

abstract class connectorFactory
{
    use coreElement;

    public static $_this = null;
    public static $_db = null;

    public static $_DB_DRIVER = null;
    public static $_DB_HOST = null;
    public static $_DB_NAME = null;
    public static $_DB_USER = null;
    public static $_DB_PASS = null;

    public static function setter()
    {
        static::set_pdo(static::$_DB_DRIVER);
    }

    public static function setter_conf($fnord)
    {
        static::$_DB_HOST = $fnord['host'];
        static::$_DB_NAME = $fnord['name'];
        static::$_DB_USER = $fnord['user'];
        static::$_DB_PASS = $fnord['pass'];
        static::set_pdo($fnord['driver']);
    }

    public static function query($fnord)
    {
        return static::$_db->prepare($fnord);
    }

    public static function set_pdo($fnord)
    {
        //268435456 2G0
        //2097152 2MO
        //$option = array(\PDO::MYSQL_ATTR_MAX_BUFFER_SIZE => 2097152);
        switch ($fnord) {
            case 'mysql':
                static::$_db = new \pdo('mysql:host='.static::$_DB_HOST.';dbname='.static::$_DB_NAME.';charset=utf8mb4', static::$_DB_USER, static::$_DB_PASS);
                break;
            case 'odbc':
                static::$_db = new \pdo('odbc:DRIVER={ODBC Driver 13 for SQL Server};SERVER='.static::$_DB_HOST.';DATABASE='.static::$_DB_NAME.';', static::$_DB_USER, static::$_DB_PASS);
                break;
            case 'sqlsrv':
                static::$_db = new \pdo('sqlsrv:Server='.static::$_DB_HOST.';Database='.static::$_DB_NAME, static::$_DB_USER, static::$_DB_PASS);
                break;
            case 'sqlite':
                static::$_db = new \pdo('sqlite:'.static::$_DB_NAME, static::$_DB_USER, static::$_DB_PASS, [\PDO::ATTR_PERSISTENT => true]);
                break;
        }
        static::$_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        static::$_db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    public static function get()
    {
        if (static::$_this === null) {
            static::$_this = new static();
        }

        return static::$_this;
    }
}
