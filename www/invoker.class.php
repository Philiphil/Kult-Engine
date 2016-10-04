<?php
    class invoker{
    	public static function require_basics($fnord="",$ext=null)
    	{
    		switch ($fnord) {
    			case '':
    				$fnord='../';
    				break;
    			case 'api':
    				$fnord='../../';
    				break;
    		}

    		include_once($fnord.'config.php');
            invoker::setter();

		    require_once(constant('controllerpath').'fonction.php');
            require_once(constant('modelpath').'invokee.class.php');
		    require_once(constant('controllerpath').'logger.class.php');
            require_once(constant('modelpath').'textes.php');
            require_once(constant('controllerpath').'texte.script.php');
            require_once(constant('controllerpath').'connector.factory.php');
		    require_once(constant('controllerpath').'connector.class.php');
		    require_once(constant('modelpath').'membre.class.php');
		    require_once(constant('modelpath').'page.class.php');
            
            invokee::setter();
            invoker::require_mods($ext);
		    connector::setter();

            session_start();
		    membre::setter();

    	}

        public static function require_mods($mods=null)
        {
            if(is_null($mods))
            {
                return 0;
            }
            foreach ($mods as $key) {
               require_once(constant('modpath').$key.constant('filespace').$key.'.handler.php');
            }
        }

        public static function try_get_conf()
        {
            //s'assure que config.php est bien include et si ce n'est pas le cas, tente de l'include lui même.
            $array[0] = '../';
            $array[1] = './';
            $array[2] = '../../';
            $array[3] = '../../../';
            $i=0;
            while(!class_exists('config'))
            {
                include_once($array[$i].'config.php');
                $i++;
            }
        }

        public static function setter()
        {
            if(!class_exists('config'))
            {
                invoker::try_get_conf();
            }

            if(!class_exists('config'))
            {
                echo 'CONFIG.PHP MISSING';
                die;
            }
            $filespace="/";
            switch (config::$systeme) {
              case "windows":
              $filespace="\\";
              break;
              case "linux":
              $filespace="/";
              break;
            }
            config::$config = config::$config ==1 ? config::$webfolder.$filespace : "";
            $base= substr(config::$file, 0, -strlen($filespace.config::$config."config.php"));
            define('viewpath', $base.$filespace.config::$webfolder.$filespace);
            define('modelpath', $base.$filespace.config::$modelfolder.$filespace);
            define('controllerpath', $base.$filespace.config::$controllerfolder.$filespace);
            define('libpath', constant('modelpath').'vendor'.$filespace);
            define('modpath', constant('controllerpath').'mods'.$filespace);
            define('htmlpath', config::$htmlfolder);
            define('filespace', $filespace);
            define('contentpath', constant('htmlpath').'content/');
            define('imagepath', constant('contentpath').'images/');
        }




    }