<?php
    class invokee{
    	public static function require_local_model()
        {

        }

        public static function require_external_basics()
        {
            require_once(constant('libpath').'syslog.php');
            require_once(constant('libpath').'password_hash.php');
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