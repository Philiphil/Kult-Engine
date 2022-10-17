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
 * @copyright Copyright (c) 2016-2222, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine\Core;

use KultEngine\Config\Config;

abstract class AbstractInvoker
{
    public static function require_mods(?array $mods = []): void
    {
        if ($mods == null) {
            return;
        }
        foreach ($mods as $key) {
            if (self::require_quick($key)) {
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
        $base = dirname(__FILE__).DS.'..';

        define('basepath', $base.DIRECTORY_SEPARATOR);

        if (Config::$webFolder && Config::$webFolder != '/') {
            define('viewpath', $base.DIRECTORY_SEPARATOR.Config::$webFolder.DIRECTORY_SEPARATOR);
        } else {
            define('viewpath', $base.DIRECTORY_SEPARATOR);
        }
        define('localpath', $base.DS.'..'.DS.Config::$localCodeFolder.DS);
        define('modelpath', constant('localpath').'model'.DS);
        define('controllerpath', dirname(__FILE__).DS);

        define('vendorpath', $base.DS.'vendor'.DS);
        define('modpath', $base.DS.'mods'.DS);
        define('imptpath', constant('localpath').'impt'.DS);
        define('tmppath', constant('controllerpath').'tmp'.DS);
        define('optnpath', constant('controllerpath').'optn'.DS);
        define('corepath', dirname(__FILE__).DS);
        define('cmdpath', constant('controllerpath').'cmd'.DIRECTORY_SEPARATOR);

        define('clipath', constant('cmdpath').'app.php');

        define('traitspath', constant('corepath').'Traits'.DIRECTORY_SEPARATOR);

        define('tpltpath', constant('imptpath').'tplt'.DIRECTORY_SEPARATOR);
        define('ctrlpath', constant('imptpath').'ctrl'.DIRECTORY_SEPARATOR);
        define('rqrdpath', constant('imptpath').'rqrd'.DIRECTORY_SEPARATOR);

        define('htmlpath', Config::$htmlFolder);
        define('contentpath', constant('htmlpath').'content/');
        define('apipath', constant('htmlpath').'api/');
        define('imagepath', constant('contentpath').'images/');

        define('debug', Config::$debug);
        define('logfile', Config::$log);
        define('default_lang', Config::$defaultLang);
        define('server_lang', Config::$serverLang);
        define('loginpage', Config::$loginPage);
        define('view', Config::$webFolder);
        define('url', substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '.php') + 4));
        define('page', substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/'), strpos($_SERVER['PHP_SELF'], '.php') + 4));

        define('host', Config::$host);
        define('db', Config::$db);
        define('user', Config::$user);
        define('pass', Config::$pass);
        define('driver', Config::$driver);
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
                $classname = strstr($impt[$i], '.', true);
                if (strripos($classname, DS)) {
                    $classname = substr($classname, strripos($classname, DS) + 1);
                }

                self::class_init($classname);
            }
        }
    }

    public static function class_init(string $key): void
    {
        if (class_exists($key)) {
            $class = $key;
        } elseif (class_exists(__NAMESPACE__.'\\'.$key)) {
            $class = __NAMESPACE__.'\\'.$key;
        } else {
            return;
        }
        $uses = class_uses($class);
        if (get_parent_class($class)) {
            $parent = get_parent_class($class);
            $uses = array_merge($uses, class_uses($parent));
        }

        if (in_array("KultEngine\CoreElementTrait", $uses) ||
                in_array("KultEngine\settable", $uses)
            ) {
            $class::init();
        }
    }

    public static function loader(string $className): bool
    {
        $className = substr($className, strripos($className, '\\') + 1);
        if (self::require_quick($className)) {
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
        spl_autoload_register('KultEngine\Core\AbstractInvoker::loader');

        define('DS', DIRECTORY_SEPARATOR);
        require_once dirname(__FILE__).DS.'..'.DS.'Config'.DS.'Config.php';

        self::setter();
        mb_internal_encoding('UTF-8');

        require_once constant('corepath').'fonction.php';
        require_once constant('traitspath').'DebuggableTrait.php';
        require_once constant('traitspath').'SettableTrait.php';
        require_once constant('traitspath').'CoreElementTrait.php';
        require_once constant('traitspath').'JsonSerializableTrait.php';

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
        require_once corepath.'Dao'.DS.'AbstractConnector.php';
        require_once corepath.'Dao'.DS.'DaoableProperty.php';
        require_once corepath.'Dao'.DS.'Id.php';
        require_once corepath.'Dao'.DS.'DaoableObject.php';
        require_once corepath.'Dao'.DS.'BaseDaoGenerator.php';
        require_once corepath.'Dao'.DS.'DaoGenerator.php';
        require_once corepath.'Dao'.DS.'DaoGeneratorSQL.php';
        require_once corepath.'Dao'.DS.'Relation'.DS.'Relation.php';
        require_once corepath.'Dao'.DS.'DaoGenerator.php';
        require_once corepath.'Dao'.DS.'DaoGeneratorSQL.php';
        require_once corepath.'Dao'.DS.'Helper'.DS.'SQLHelper.php';
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
                require_once constant('corepath').'Hook'.DS.'Hook.php';
                require_once constant('corepath').'Hook'.DS.'HookableTrait.php';
                require_once constant('corepath').'Hook'.DS.'HookExecutor.php';
                self::class_init("KultEngine\Core\Hook\Hook");

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
                require_once constant('corepath').'Dao'.DS.'AbstractConnector.php';
                self::class_init($f);

                return true;
            case 'Session':
                ini_set('session.use_strict_mode', true);
                ini_set('session.use_cookies', true);
                ini_set('session.use_only_cookies ', true);
                ini_set('session.cookie_httponly', true);
                ini_set('session.use_trans_sid', false);
                ini_set('session.save_handler', 'files');
                session_save_path(sys_get_temp_dir());
                require_once constant('corepath').'Security'.DS.'JWT'.DS.'JWTHeader.php';
                require_once constant('corepath').'Security'.DS.'JWT'.DS.'JWTPayload.php';
                require_once constant('corepath').'Security'.DS.'JWT'.DS.'JWT.php';
                require_once constant('corepath').'Security'.DS.'JWT'.DS.'JWTService.php';
                require_once constant('corepath').'Security'.DS.'Session'.DS.'SecureSessionHandler.php';
                require_once constant('corepath').'Security'.DS.'Session'.DS.'AbstractSession.php';
              //  self::class_init($f);

                return true;
            case 'Buffer':
                require_once constant('corepath').'Buffer.php';
                self::class_init($f);

                return true;
            case 'Router':
            case 'Route':
                require_once constant('corepath').'Router'.DS.'Router.php';
                require_once constant('corepath').'Router'.DS.'Route.php';
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
