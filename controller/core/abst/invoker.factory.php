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

abstract class invokerFactory
{
    public static function require_mods($mods = [])
    {
        foreach ($mods as $key) {
            if (self::require_quick($key) == true) {
                continue;
            }
            switch ($key) {
                default:
                    require_once constant('modpath').$key.DIRECTORY_SEPARATOR.$key.'.handler.php';
                    self::class_init($key);
                    self::class_init($key.'Handler');
                    break;
            }
        }
    }

    public static function setter()
    {
        $base = dirname(config::$file);

        define('multi', config::$multi);
        define('basepath', $base.DIRECTORY_SEPARATOR);

        if (!config::$multi) {
            if (config::$webfolder && config::$webfolder != "/" ) {
                define('viewpath', $base.DIRECTORY_SEPARATOR.config::$webfolder.DIRECTORY_SEPARATOR);
            } else {
                define('viewpath', $base.DIRECTORY_SEPARATOR);
            }
        } else {
            $bfr = debug_backtrace();
            define('viewpath', $base.DIRECTORY_SEPARATOR.substr($bfr[count($bfr) - 1]['file'], strlen(basepath), strpos(substr($bfr[count($bfr) - 1]['file'], strlen(basepath)), DIRECTORY_SEPARATOR)).DIRECTORY_SEPARATOR);
        }
        define('modelpath', $base.DIRECTORY_SEPARATOR.config::$modelfolder.DIRECTORY_SEPARATOR);
        define('controllerpath', $base.DIRECTORY_SEPARATOR.config::$controllerfolder.DIRECTORY_SEPARATOR);

        define('vendorpath', constant('controllerpath').'vendor'.DIRECTORY_SEPARATOR);
        define('modpath', constant('controllerpath').'mods'.DIRECTORY_SEPARATOR);
        if (!config::$multi) {
            define('imptpath', constant('controllerpath').'impt'.DIRECTORY_SEPARATOR);
        } else {
            define('imptpath', constant('viewpath').'impt'.DIRECTORY_SEPARATOR);
        }
        define('tmppath', constant('controllerpath').'tmp'.DIRECTORY_SEPARATOR);
        define('optnpath', constant('controllerpath').'optn'.DIRECTORY_SEPARATOR);
        define('corepath', constant('controllerpath').'core'.DIRECTORY_SEPARATOR);
        define('kultpath', constant('controllerpath').'kult'.DIRECTORY_SEPARATOR);

        define('clipath', constant('kultpath').'app.php');

        define('abstpath', constant('corepath').'abst'.DIRECTORY_SEPARATOR);
        define('itfcpath', constant('corepath').'itfc'.DIRECTORY_SEPARATOR);
        
        define('tpltpath', constant('imptpath').'tplt'.DIRECTORY_SEPARATOR);
        define('ctrltpath', constant('imptpath').'ctrl'.DIRECTORY_SEPARATOR);

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
        define('page', substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/'), strpos($_SERVER['PHP_SELF'], '.php') + 4));

        define('host', config::$host);
        define('db', config::$db);
        define('user', config::$user);
        define('pass', config::$pass);
        define('driver', config::$driver);
    }

    public static function require_impt()
    {
        $impt = scandir(constant('imptpath'));
        for ($i = 0; $i < count($impt); $i++) {
            if (is_dir(constant('imptpath').$impt[$i]) &&
            ($impt[$i] != '.' && $impt[$i] != '..' && $impt[$i] != 'tplt')) {
                $tmp = scandir(constant('imptpath').$impt[$i]);
                $bfr = [];
                foreach ($tmp as $key) {
                    if ($key == '.' || $key == '..') {
                        continue;
                    }
                    $bfr[count($bfr)] = $impt[$i].DIRECTORY_SEPARATOR.$key;
                }

                $impt = array_merge($impt, $bfr);
            } elseif (contains('.load.', $impt[$i])) {
                include constant('imptpath').$impt[$i];
                self::class_init(strstr($impt[$i], '.', true));
            }
        }
    }

    public static function class_init($key)
    {
        if (class_exists(__NAMESPACE__.'\\'.$key)) {
            if (in_array(__NAMESPACE__."\coreElement", class_uses(__NAMESPACE__.'\\'.$key)) || in_array(__NAMESPACE__."\settable", class_uses(__NAMESPACE__.'\\'.$key)) || in_array('coreElement', class_uses(__NAMESPACE__.'\\'.$key)) || in_array('settable', class_uses(__NAMESPACE__.'\\'.$key))) {
                $d = __NAMESPACE__.'\\'.$key;
                $d::init();
            }
        }
    }

    public static function loader($className)
    {
        $className = substr($className, strripos($className, '\\') + 1);
        if (self::require_quick($className) == true) {
            return true;
        }

        $prefix[0] = constant('abstpath');
        $prefix[1] = constant('itfcpath');
        $prefix[2] = constant('corepath');
        $prefix[3] = constant('imptpath');
        $prefix[4] = constant('vendorpath');
        $prefix[5] = constant('controllerpath');
        $prefix[6] = constant('optnpath');
        $prefix[7] = constant('modpath').$className.DIRECTORY_SEPARATOR;
        $sufix[0] = '';
        $sufix[1] = '.class';
        $sufix[2] = '.trait';
        $sufix[3] = '.handler';
        $sufix[4] = '.factory';
        $sufix[5] = '.load';
        foreach ($prefix as $a) {
            foreach ($sufix as $b) {
                if (file_exists($a.$className.$b.'.php')) {
                    include_once $a.$className.$b.'.php';
                    self::class_init($className);

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
        $file = substr($errfile, strripos($errfile, DIRECTORY_SEPARATOR) + 1);
        $file = substr($file, 0, strpos($file, '.'));
        if (class_exists(__NAMESPACE__.'\\'.$file) && in_array(__NAMESPACE__.'\\'.'coreElement', class_uses(__NAMESPACE__.'\\'.$file)) || in_array(__NAMESPACE__.'\\'.'debuggable', class_uses(__NAMESPACE__.'\\'.$file))) {
            $e = new \ReflectionClass(__NAMESPACE__.'\\'.$file);
            $f = $e->getMethod('debug');
            $f->invoke(null);
        }

        $status = $errno == E_USER_ERROR || $errno == E_ERROR ? '<b>FATAL</b><br>' : '';

        $saying = $errstr != '' ? $errstr : $errno;
        $saying = contains('(output', $saying) ? substr($saying, 0, strpos($saying, '(output')) : $saying;

        echo '<br> <b>E</b> : '.$saying.'<br>';
        echo 'L : <b>'.$errline.'</b> - F : <b>'.$file.'</b><br>';
        echo $status;
        die();
    }

    public static function require_local_model()
    {
        require_once constant('optnpath').'daoableObject.class.php';
        require_once constant('abstpath').'daoGenerator.factory.php';
        $model = scandir(constant('modelpath'));
        foreach ($model as $key) {
            if (contains('.class.', $key)) {
                include constant('modelpath').DIRECTORY_SEPARATOR.$key;
            }
        }
    }

    public static function require_vendor()
    {
        $ctrl = scandir(constant('vendorpath'));
        foreach ($ctrl as $key) {
            if (contains('.php', $key)) {
                include constant('vendorpath').DIRECTORY_SEPARATOR.$key;
            }
        }
    }

    public static function require_local_controler()
    {
        $ctrl = scandir(constant('ctrltpath'));
        foreach ($ctrl as $key) {
            if (contains('.php', $key)) {
                include constant('ctrltpath').DIRECTORY_SEPARATOR.$key;
            }
        }
    }

    public static function require_quick($f)
    {
        switch ($f) {
            case 'hook':
                require_once constant('corepath').'hook.class.php';
                self::class_init($f);

                return true;
            case 'logger':
                require_once constant('corepath').'logger.class.php';
                self::class_init($f);

                return true;
            case 'text':
                require_once constant('imptpath').'lang.php';
                require_once constant('corepath').'text.class.php';
                self::class_init($f);

                return true;
            case 'connector':
                require_once constant('abstpath').'connector.factory.php';
                self::class_init($f);

                return true;
            case 'session':
                require_once constant('abstpath').'session.factory.php';
                self::class_init($f);

                return true;
            case 'buffer':
                require_once constant('corepath').'buffer.class.php';
                self::class_init($f);

                return true;
            case 'router':
                require_once constant('corepath').'router.class.php';
                require_once constant('imptpath').'route.php';
                self::class_init($f);

                return true;

            case 'webService':
            case 'webservice':
                require_once constant('corepath').'webService.class.php';
                self::class_init($f);

                return true;
            default: return false;
        }
    }
}
