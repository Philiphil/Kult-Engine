<?php

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
        self::$_db = new \pdo('mysql:dbname='.self::$_DB_NAME.';host='.self::$_DB_HOST.';charset=utf8mb4', self::$_DB_USER, self::$_DB_PASS);
        self::$_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$_db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }
}
