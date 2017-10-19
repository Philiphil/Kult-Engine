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

abstract class connectorFactory
{
    protected static $_db = null;
    protected static $_DB_HOST = host;
    protected static $_DB_NAME = db;
    protected static $_DB_USER = user;
    protected static $_DB_PASS = pass;

    public static function setter()
    {
        self::$_DB_HOST = constant('host');
        self::$_DB_NAME = constant('db');
        self::$_DB_USER = constant('user');
        self::$_DB_PASS = constant('pass');
        self::set_pdo(constant('driver'));
    }

    public static function setter_conf($fnord)
    {
        self::$_DB_HOST = $fnord['host'];
        self::$_DB_NAME = $fnord['name'];
        self::$_DB_USER = $fnord['user'];
        self::$_DB_PASS = $fnord['pass'];
        self::set_pdo($fnord['driver']);
    }

    public static function query($fnord)
    {
        return self::$_db->prepare($fnord);
    }

    public static function set_pdo($fnord)
    {
        //268435456 2G0
        //2097152 2MO
        //$option = array(\PDO::MYSQL_ATTR_MAX_BUFFER_SIZE => 2097152);
        switch ($fnord) {
            case 'mysql':
                self::$_db = new \pdo('mysql:host='.self::$_DB_HOST.';dbname='.self::$_DB_NAME.';charset=utf8mb4', self::$_DB_USER, self::$_DB_PASS);
                break;
            case 'odbc':
                self::$_db = new \pdo('odbc:DRIVER={ODBC Driver 13 for SQL Server};SERVER='.self::$_DB_HOST.';DATABASE='.self::$_DB_NAME.';', self::$_DB_USER, self::$_DB_PASS);
                break;
            case 'sqlsrv':
                self::$_db = new \pdo('sqlsrv:Server='.self::$_DB_HOST.';Database='.self::$_DB_NAME, self::$_DB_USER, self::$_DB_PASS);
                break;
            case 'sqlite':
                self::$_db = new \pdo('sqlite:'.self::$_DB_NAME, self::$_DB_USER, self::$_DB_PASS, [\PDO::ATTR_PERSISTENT => true]);
                break;
        }
        self::$_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$_db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }
}
