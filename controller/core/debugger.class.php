<?php

namespace kult_engine;

abstract class debugger
{
    use singleton;
    use debuggable;
    use settable;
    use injectable;
    use inquisitable;
    public static $_debug = 1;

    public static function setter()
    {
        self::$_debug = constant('debug');
        set_error_handler(__NAMESPACE__.'\debugger::handler');

        return 0;
    }

    public static function handler($errno, $errstr, $errfile, $errline)
    {
        self::init_required();

        if (!self::$_debug) {
            if ($errno != E_USER_ERROR || $errno != E_ERROR) {
                return;
            }
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

        if (class_exists(__NAMESPACE__.'\\'.$file) && in_array(__NAMESPACE__.'\\'.'debugable', class_uses(__NAMESPACE__.'\\'.$file))) {
            $e = new \ReflectionClass(__NAMESPACE__.'\\'.$file);
            $f = $e->getMethod('debug');
            $f->invoke(null);
        }
        if ($errno == E_USER_ERROR || $errno == E_ERROR) {
            if ($errno == E_USER_ERROR) {
                self::inquisite('flag');
            }
            die;
        }
    }
}
