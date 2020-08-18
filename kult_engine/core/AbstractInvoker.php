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

abstract class AbstractInvoker
{
    public static function require_mods(?array $mods = []): void
    {
        if ($mods == null) {
            return;
        }
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

    public static function setter(): void
    {
        $base = dirname(config::$file);

        define('multi', config::$multi);
        define('basepath', $base.DIRECTORY_SEPARATOR);
        define('DS', DIRECTORY_SEPARATOR);

        if (!config::$multi) {
            if (config::$webFolder && config::$webFolder != '/') {
                define('viewpath', $base.DIRECTORY_SEPARATOR.config::$webFolder.DIRECTORY_SEPARATOR);
            } else {
                define('viewpath', $base.DIRECTORY_SEPARATOR);
            }
        } else {
            $bfr = debug_backtrace();
            define('viewpath', $base.DIRECTORY_SEPARATOR.substr($bfr[count($bfr) - 1]['file'], strlen(constant("basepath")), strpos(substr($bfr[count($bfr) - 1]['file'], strlen(constant("basepath"))), DIRECTORY_SEPARATOR)).DIRECTORY_SEPARATOR);
        }
        define('localpath', $base.DIRECTORY_SEPARATOR.config::$localCodeFolder.DIRECTORY_SEPARATOR);
        define('modelpath', constant('localpath')."model".DIRECTORY_SEPARATOR);
        define('controllerpath', $base.DIRECTORY_SEPARATOR.config::$kult_engineFolder.DIRECTORY_SEPARATOR);

        define('vendorpath', constant('controllerpath').'vendor'.DIRECTORY_SEPARATOR);
        define('modpath', constant('controllerpath').'mods'.DIRECTORY_SEPARATOR);
        if (!config::$multi) {
            define('imptpath', constant('localpath').'impt'.DIRECTORY_SEPARATOR);
        } else {
            define('imptpath', constant('viewpath')."local".DIRECTORY_SEPARATOR.'impt'.DIRECTORY_SEPARATOR);
        }
        define('tmppath', constant('controllerpath').'tmp'.DIRECTORY_SEPARATOR);
        define('optnpath', constant('controllerpath').'optn'.DIRECTORY_SEPARATOR);
        define('corepath', constant('controllerpath').'core'.DIRECTORY_SEPARATOR);
        define('cmdpath', constant('controllerpath').'cmd'.DIRECTORY_SEPARATOR);

        define('clipath', constant('cmdpath').'app.php');

        define('traitspath', constant('corepath').'traits'.DIRECTORY_SEPARATOR);

        define('tpltpath', constant('imptpath').'tplt'.DIRECTORY_SEPARATOR);
        define('ctrlpath', constant('imptpath').'ctrl'.DIRECTORY_SEPARATOR);
        define('rqrdpath', constant('imptpath').'rqrd'.DIRECTORY_SEPARATOR);

        define('htmlpath', config::$htmlFolder);
        define('contentpath', constant('htmlpath').'content/');
        define('apipath', constant('htmlpath').'api/');
        define('imagepath', constant('contentpath').'images/');

        define('debug', config::$debug);
        define('logfile', config::$log);
        define('default_lang', config::$defaultLang);
        define('server_lang', config::$serverLang);
        define('loginpage', config::$loginPage);
        define('view', config::$webFolder);
        define('url', substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '.php') + 4));
        define('page', substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/'), strpos($_SERVER['PHP_SELF'], '.php') + 4));

        define('host', config::$host);
        define('db', config::$db);
        define('user', config::$user);
        define('pass', config::$pass);
        define('driver', config::$driver);
    }

    public static function require_impt(): void
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

    public static function class_init(string $key): void
    {
        if (class_exists(__NAMESPACE__.'\\'.$key)) {
            if (in_array(__NAMESPACE__."\CoreElementTrait", class_uses(__NAMESPACE__.'\\'.$key)) || in_array(__NAMESPACE__."\settable", class_uses(__NAMESPACE__.'\\'.$key)) || in_array('CoreElementTrait', class_uses(__NAMESPACE__.'\\'.$key)) || in_array('settable', class_uses(__NAMESPACE__.'\\'.$key))) {
                $d = __NAMESPACE__.'\\'.$key;
                $d::init();
            }
        }
    }

    public static function loader(string $className): bool
    {
        $className = substr($className, strripos($className, '\\') + 1);
        if (self::require_quick($className) == true) {
            return true;
        }

        $prefix[0] = constant('traitspath');
        $prefix[1] = constant('corepath');
        $prefix[2] = constant('imptpath');
        $prefix[3] = constant('vendorpath');
        $prefix[4] = constant('controllerpath');
        $prefix[5] = constant('optnpath');
        $prefix[6] = constant('modpath').$className.DIRECTORY_SEPARATOR;
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

    public static function _requireBase(): void
    {
        self::is_ke_runnable();
        self::setter();
        spl_autoload_register(__NAMESPACE__.'\Invoker::loader');
        mb_internal_encoding('UTF-8');

        require_once constant('corepath').'fonction.php';
        require_once constant('traitspath').'DebuggableTrait.php';
        require_once constant('traitspath').'SettableTrait.php';
        require_once constant('traitspath').'QueryableTrait.php';
        require_once constant('traitspath').'CoreElementTrait.php';

        self::require_quick('Hook');
    }

    public static function is_ke_runnable(): void
    {
        $needed = ['mbstring', 'json', 'PDO', 'Reflection', 'openssl', 'session'];
        $loaded = get_loaded_extensions();
        foreach ($needed as $key) {
            if (!in_array($key, $loaded)) {
                echo $key.' not found, KULT ENGINE cant run';
                exit();
            }
        }
    }

    public static function error($errno, $errstr, $errfile, $errline): void
    {
        if (!constant('debug')) {
            if ($errno != E_USER_ERROR || $errno != E_ERROR) {
                return;
            }
            buffer::delete();
            echo '<br><b>FATAL</b>';
            exit;
        }
        $file = substr($errfile, strripos($errfile, DIRECTORY_SEPARATOR) + 1);
        $file = substr($file, 0, strpos($file, '.'));
        if (class_exists(__NAMESPACE__.'\\'.$file) && in_array(__NAMESPACE__.'\\'.'CoreElementTrait', class_uses(__NAMESPACE__.'\\'.$file)) || in_array(__NAMESPACE__.'\\'.'debuggable', class_uses(__NAMESPACE__.'\\'.$file))) {
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
        exit();
    }

    public static function require_local_model(): void
    {
        require_once corepath.'dao'.DS.'AbstractConnector.php';
        require_once corepath.'dao'.DS.'DaoableObject.php';
        require_once corepath.'dao'.DS.'DaoGenerator.php';
        require_once corepath.'dao'.DS.'DaoGeneratorSQL.php';
        require_once corepath.'dao'.DS.'helper'.DS.'SQLHelper.php';
        $model = scandir(constant('modelpath'));
        foreach ($model as $key) {
            if (contains('.class.', $key)) {
                include constant('modelpath').DIRECTORY_SEPARATOR.$key;
            }
        }
    }

    public static function require_vendor(): void
    {
        $ctrl = scandir(constant('vendorpath'));
        foreach ($ctrl as $key) {
            if (contains('.php', $key)) {
                include constant('vendorpath').DIRECTORY_SEPARATOR.$key;
            }
        }
    }

    public static function require_local_controler(): void
    {
        $ctrl = scandir(constant('ctrlpath'));
        foreach ($ctrl as $key) {
            if (contains('.php', $key)) {
                include constant('ctrlpath').DIRECTORY_SEPARATOR.$key;
            }
        }
    }

    public static function require_quick(string $f): bool
    {
        switch ($f) {
            case 'Hook':
                require_once constant('corepath').'hook'.DS.'Hook.php';
                require_once constant('corepath').'hook'.DS.'HookableTrait.php';
                require_once constant('corepath').'hook'.DS.'HookExecutor.php';
                self::class_init($f);

                return true;
            case 'Logger':
                require_once constant('corepath').'Logger.php';
                self::class_init($f);

                return true;
            case 'text':
                require_once constant('imptpath').'i18n.php';
                require_once constant('corepath').'Text.php';
                self::class_init($f);

                return true;
            case 'connector':
                require_once corepath.'dao'.DS.'AbstractConnector.php';
                self::class_init($f);

                return true;
            case 'AbstractSession':
                require_once constant('abstpath').'AbstractSession.php';
                self::class_init($f);

                return true;
            case 'Buffer':
                require_once constant('corepath').'Buffer.php';
                self::class_init($f);

                return true;
            case 'Router':
            case 'Route':
                require_once constant('corepath')."route".DS.'Router.php';
                require_once constant('corepath')."route".DS.'Route.php';
                require_once constant('imptpath').'rqrd'.DS.'route.php';
                self::class_init($f);

                return true;

            case 'WebService':
                require_once constant('corepath').'WebService.php';
                self::class_init($f);

                return true;
            default: return false;
        }
    }

    public static function allowCORS()
    {
        header('Access-Control-Allow-Origin: *');
    }
}
