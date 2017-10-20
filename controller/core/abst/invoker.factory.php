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

abstract class invokerFactory
{
    public static function require_mods($mods = null)
    {
        if (is_null($mods)) {
            return 0;
        }

        foreach ($mods as $key) {
            switch ($key) {
                case 'router':
                    require_once constant('corepath').'router.class.php';
                    require_once constant('imptpath').'route.php';
                    router::init();
                    break;
                default:
                    require_once constant('modpath').$key.constant('filespace').$key.'.handler.php';
                    break;
            }
        }
    }

    public static function setter()
    {
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

        define('multi', config::$multi);

        define('filespace', $filespace);
        define('basepath', $base.$filespace);

        if (!config::$multi) {
            define('viewpath', $base.$filespace.config::$webfolder.$filespace);
        } else {
            $bfr = debug_backtrace();
            define('viewpath', $base.$filespace.substr($bfr[count($bfr) - 1]['file'], strlen(basepath), strpos(substr($bfr[count($bfr) - 1]['file'], strlen(basepath)), filespace)).$filespace);
        }
        define('modelpath', $base.$filespace.config::$modelfolder.$filespace);
        define('controllerpath', $base.$filespace.config::$controllerfolder.$filespace);

        define('vendorpath', constant('controllerpath').'vendor'.$filespace);
        define('modpath', constant('controllerpath').'mods'.$filespace);
        if (!config::$multi) {
            define('imptpath', constant('controllerpath').'impt'.$filespace);
        } else {
            define('imptpath', constant('viewpath').'impt'.$filespace);
        }
        define('tmppath', constant('controllerpath').'tmp'.$filespace);
        define('optnpath', constant('controllerpath').'optn'.$filespace);
        define('corepath', constant('controllerpath').'core'.$filespace);
        define('kultpath', constant('controllerpath').'kult'.$filespace);

        define('abstpath', constant('corepath').'abst'.$filespace);
        define('itfcpath', constant('corepath').'itfc'.$filespace);
        define('imp2path', constant('corepath').'imp2'.$filespace);

        define('tpltpath', constant('imptpath').'tplt'.$filespace);
        define('ctrltpath', constant('imptpath').'ctrl'.$filespace);

        define('htmlpath', config::$htmlfolder);
        define('contentpath', constant('htmlpath').'content/');
        define('apipath', constant('htmlpath').'api/');
        define('imagepath', constant('contentpath').'images/');

        define('debug', config::$debug);
        define('logfile', config::$log);
        define('default_lang', config::$default_lang);
        define('server_lang', config::$server_lang);
        define('loginpage', config::$login_page);
        define('view', config::$webfolder);
        define('url', substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '.php') + 4));
        define('page', substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1, strpos($_SERVER['PHP_SELF'], '.php') + 4));

        define('host', config::$host);
        define('db', config::$db);
        define('user', config::$user);
        define('pass', config::$pass);
        define('driver', config::$driver);
    }

    public static function require_impt()
    {
        $impt = scandir(constant('imptpath'));
        foreach ($impt as $key) {
            if (contains('.load.', $key)) {
                include constant('imptpath').$key;
                if (class_exists(__NAMESPACE__.'\\'.strstr($key, '.', true))) {
                    if (in_array(__NAMESPACE__."\coreElement", class_uses(__NAMESPACE__.'\\'.strstr($key, '.', true))) || in_array(__NAMESPACE__."\settable", class_uses(__NAMESPACE__.'\\'.strstr($key, '.', true))) || in_array('coreElement', class_uses(__NAMESPACE__.'\\'.strstr($key, '.', true))) || in_array('settable', class_uses(__NAMESPACE__.'\\'.strstr($key, '.', true)))) {
                        $d = __NAMESPACE__.'\\'.strstr($key, '.', true);
                        $d::init();
                    }
                }
            }
        }
    }

    public static function loader($className)
    {
        $className = substr($className, strripos($className, '\\') + 1);
        $prefix[0] = constant('abstpath');
        $prefix[1] = constant('itfcpath');
        $prefix[2] = constant('corepath');
        $prefix[3] = constant('imptpath');
        $prefix[4] = constant('vendorpath');
        $prefix[5] = constant('controllerpath');
        $prefix[6] = constant('optnpath');
        $prefix[7] = constant('modpath').$className.constant('filespace');
        $sufix[0] = '';
        $sufix[1] = '.class';
        $sufix[2] = '.trait';
        $sufix[3] = '.interface';
        $sufix[4] = '.handler';
        $sufix[5] = '.factory';
        $sufix[6] = '.load';
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

    public static function require_base()
    {
        self::is_ke_runnable();
        self::setter();
        spl_autoload_register(__NAMESPACE__.'\invoker::loader');
        mb_internal_encoding('UTF-8');
        require_once constant('corepath').'fonction.php';
        require_once constant('itfcpath').'debuggable.trait.php';
        require_once constant('itfcpath').'settable.trait.php';
        require_once constant('abstpath').'connector.factory.php';
        require_once constant('itfcpath').'queryable.trait.php';
        require_once constant('itfcpath').'coreElement.trait.php';
    }

    public static function is_ke_runnable()
    {
        $needed = ['mbstring', 'json', 'PDO', 'Reflection', 'openssl', 'session'];
        $loaded = get_loaded_extensions();
        foreach ($needed as $key) {
            if (!in_array($key, $loaded)) {
                echo $key.' not found, KULT ENGINE cant run';
                die();
            }
        }
    }

    public static function error($errno, $errstr, $errfile, $errline)
    {
        if (!constant('debug')) {
            if ($errno != E_USER_ERROR || $errno != E_ERROR) {
                return;
            }
            buffer::delete();
            echo '<br><b>FATAL</b>';
            die;
        }
        $file = substr($errfile, strripos($errfile, constant('filespace')) + 1);
        $file = substr($file, 0, strpos($file, '.'));
        $status = $errno == E_USER_ERROR || $errno == E_ERROR ? '<b>FATAL</b><br>' : '';

        $saying = $errstr != '' ? $errstr : $errno;
        $saying = contains('(output', $saying) ? substr($saying, 0, strpos($saying, '(output')) : $saying;

        echo '<br> <b>E</b> : '.$saying.'<br>';
        echo 'L : <b>'.$errline.'</b> - F : <b>'.$file.'</b><br>';
        echo $status;

        if (class_exists(__NAMESPACE__.'\\'.$file) && in_array(__NAMESPACE__.'\\'.'coreElement', class_uses(__NAMESPACE__.'\\'.$file)) || in_array(__NAMESPACE__.'\\'.'debuggable', class_uses(__NAMESPACE__.'\\'.$file))) {
            $e = new \ReflectionClass(__NAMESPACE__.'\\'.$file);
            $f = $e->getMethod('debug');
            $f->invoke(null);
        }
        die();
    }

    public static function require_local_model()
    {
        require_once constant('optnpath').'daoableObject.class.php';
        require_once constant('optnpath').'daoGenerator.class.php';
        $model = scandir(constant('modelpath'));
        foreach ($model as $key) {
            if (contains('.class.', $key)) {
                include constant('modelpath').constant('filespace').$key;
            }
        }
    }

    public static function require_local_controler()
    {
        $ctrl = scandir(constant('ctrltpath'));
        foreach ($ctrl as $key) {
            if (contains('.php', $key)) {
                include constant('ctrltpath').constant('filespace').$key;
            }
        }
    }
}
