<?php

class connectorFactory extends PDO{
    public static $_db = null;
    public static $_DB_HOST;
    public static $_DB_NAME;
    public static $_DB_USER;
    public static $_DB_PASS;

    public static function setter()
    {

        self::$_DB_HOST = constant('host');
        self::$_DB_NAME = constant('db');
        self::$_DB_USER = constant('user');
        self::$_DB_PASS = constant('pass');

       self::$_db = new pdo('mysql:dbname='.self::$_DB_NAME.';host='.self::$_DB_HOST.';charset=utf8',self::$_DB_USER,self::$_DB_PASS, array(PDO::MYSQL_ATTR_MAX_BUFFER_SIZE => 16777216));
       self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       self::$_db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}