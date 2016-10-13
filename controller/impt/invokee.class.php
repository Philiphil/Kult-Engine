<?php
namespace kult_engine;
use kult_engine\config;
use kult_engine\singleton;
use kult_engine\debugable;

class invokee{
    use singleton;
    use debugable;
    public static function require_local_model()
    {

    }
    
    public static function require_external_basics()
    {
        require_once(constant('vendorpath').'syslog.php');
        require_once(constant('vendorpath').'password_hash.php');
    }

    public static function setter()
    {
        define('host', config::$host);
        define('db', config::$db);
        define('user', config::$user);
        define('pass', config::$pass);

        invokee::require_local_model();
        invokee::require_external_basics();
    }
}