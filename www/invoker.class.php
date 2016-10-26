<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016
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
 * @copyright Copyright (c) 2016, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */


namespace kult_engine;

abstract class invoker
{
    public static function require_basics($fnord = '', $ext = null)
    {
        switch ($fnord) {
           case '':
           $fnord = '../';
           break;
           case 'api':
           $fnord = '../../';
           break;
         }

        include_once $fnord.'config.php';
        self::setter();
        spl_autoload_register(__NAMESPACE__.'\invoker::loader');

        require_once constant('corepath').'fonction.php';
        require_once constant('corepath').'debuggable.trait.php';
        require_once constant('corepath').'singleton.trait.php';
        require_once constant('corepath').'settable.trait.php';
        require_once constant('corepath').'injectable.trait.php';
        require_once constant('corepath').'inquisitable.trait.php';

        require_once constant('corepath').'debugger.class.php';
        debugger::init();

        require_once constant('imptpath').'invokee.class.php';
        invokee::init();

        require_once constant('corepath').'logger.class.php';

        require_once constant('imptpath').'lang.php';
        require_once constant('corepath').'texte.script.php';

        require_once constant('corepath').'connector.factory.php';
        require_once constant('imptpath').'connector.class.php';
        connector::init();

        require_once constant('corepath').'router.php';
        require_once constant('imptpath').'route.php';
        router::init();

        require_once constant('imptpath').'page.class.php';

        require_once constant('corepath').'membre.class.php';
        require_once constant('corepath').'inquisitor.class.php';

        session_start();
        membre::init();
        inquisitor::init();
        self::require_mods($ext);
    }

    public static function require_lightweight($fnord = '', $core = null, $ext = null)
    {
        switch ($fnord) {
            case '':
            $fnord = '../';
            break;
            case 'api':
            $fnord = '../../';
            break;
        }
        include_once $fnord.'config.php';
        self::setter();
        spl_autoload_register(__NAMESPACE__.'\invoker::loader');
        require_once constant('corepath').'fonction.php';
        require_once constant('corepath').'debuggable.trait.php';
        require_once constant('corepath').'singleton.trait.php';
        require_once constant('corepath').'settable.trait.php';
        require_once constant('corepath').'injectable.trait.php';
        require_once constant('corepath').'inquisitable.trait.php';

        require_once constant('imptpath').'invokee.class.php';
        invokee::init();
        if (is_array($core)) {
            if (\in_array('debugger', $core)) {
                require_once constant('corepath').'debugger.class.php';
                debugger::init();
            }
            if (\in_array('logger', $core)) {
                require_once constant('corepath').'logger.class.php';
            }

            if (\in_array('lang', $core)) {
                require_once constant('imptpath').'lang.php';
                require_once constant('corepath').'texte.script.php';
            }

            if (\in_array('connector', $core)) {
                require_once constant('corepath').'connector.factory.php';
                require_once constant('imptpath').'connector.class.php';
                connector::init();
            }

            if (\in_array('router', $core)) {
                require_once constant('corepath').'router.php';
                require_once constant('imptpath').'route.php';
                router::init();
            }

            if (\in_array('page', $core)) {
                require_once constant('imptpath').'page.class.php';
            }

            if (\in_array('session', $core)) {
                require_once constant('corepath').'membre.class.php';
                session_start();
                membre::init();
            } else {
                session_start();
            }
            
            if(\in_array('inquisitor'))
            {
                 require_once constant('corepath').'inquisitor.class.php';
                 inquisitor::init();
            }
        } else {
            session_start();
        }
        if (is_array($ext)) {
            self::require_mods($ext);
        }
    }

    public static function require_mods($mods = null)
    {
        if (is_null($mods)) {
            return 0;
        }
        foreach ($mods as $key) {
            require_once constant('modpath').$key.constant('filespace').$key.'.handler.php';
        }
    }

    public static function config_autoload()
    {
        $array[0] = '../';
        $array[1] = './';
        $array[2] = '../../';
        $array[3] = '../../../';
        $i = 0;
        while (!class_exists(config::class, false) || $i < count($array)) {
            include_once $array[$i].'config.php';
            $i++;
        }
    }

    public static function setter()
    {
        if (!class_exists(config::class, false)) {
            self::config_autoload();
        }

        if (!class_exists(config::class)) {
            trigger_error('config file not found', E_USER_ERROR);
        }
        $filespace = '/';
        switch (config::$systeme) {
            case 'windows':
            $filespace = '\\';
            break;
            case 'linux':
            $filespace = '/';
            break;
        }
        config::$config = config::$config == 1 ? config::$webfolder.$filespace : '';
        $base = substr(config::$file, 0, -strlen($filespace.config::$config.'config.php'));
        define('viewpath', $base.$filespace.config::$webfolder.$filespace);
        define('modelpath', $base.$filespace.config::$modelfolder.$filespace);
        define('controllerpath', $base.$filespace.config::$controllerfolder.$filespace);
        define('vendorpath', constant('controllerpath').'vendor'.$filespace);
        define('modpath', constant('controllerpath').'mods'.$filespace);

        define('corepath', constant('controllerpath').'core'.$filespace);
        define('imptpath', constant('controllerpath').'impt'.$filespace);

        define('htmlpath', config::$htmlfolder);
        define('filespace', $filespace);
        define('contentpath', constant('htmlpath').'content/');
        define('imagepath', constant('contentpath').'images/');
        define('debug', config::$debug);
    }

    public static function loader($className)
    {
        $className = substr($className, strripos($className, '\\') + 1);
        $prefix[0] = constant('corepath');
        $prefix[1] = constant('imptpath');
        $prefix[2] = constant('vendorpath');
        $prefix[3] = constant('controllerpath');
        $prefix[4] = constant('modpath').$className.constant('filespace');
        $sufix[0] = '';
        $sufix[1] = '.class';
        $sufix[2] = '.trait';
        $sufix[3] = '.interface';
        $sufix[4] = '.handler';
        $sufix[5] = '.factory';
        foreach ($prefix as $a) {
            foreach ($sufix as $b) {
                if (file_exists($a.$className.$b.'.php')) {
                    include_once $a.$className.$b.'.php';

                    return true;
                }
            }
        }

        return false;
    }
}
