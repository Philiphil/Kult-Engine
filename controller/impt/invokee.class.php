<?php

namespace kult_engine;

class invokee
{
    use singleton;
    use debugable;
    use settable;
    use injectable;

    public static function require_local_model()
    {
        self::init_required();
    }

    public static function require_external_basics()
    {
        self::init_required();
        require_once constant('vendorpath').'syslog.php';
        require_once constant('vendorpath').'password_hash.php';
    }

    public static function setter()
    {
        define('host', config::$host);
        define('db', config::$db);
        define('user', config::$user);
        define('pass', config::$pass);

        self::require_local_model();
        self::require_external_basics();
    }
}
