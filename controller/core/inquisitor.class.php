<?php

namespace kult_engine;

abstract class inquisitor
{
    use singleton;
    use debuggable;
    use settable;
    use injectable;
    public static $_tempo = [];
    public static $_watcher = 0;
    public static $_flag = 0;
    public static $_deny = 0;

    public static function setter()
    {
        self::$_watcher = isset($_SESSION['watcher']) ? intval($_SESSION['watcher']) : 0;
        self::$_flag = isset($_SESSION['flag']) ? intval($_SESSION['flag']) : 0;
        self::$_deny = isset($_SESSION['deny']) ? intval($_SESSION['deny']) : 0;
        self::$_tempo = isset($_SESSION['tempo']) ? $_SESSION['tempo'] : [];
        self::compute();
    }

    public static function save()
    {
        $_SESSION['watcher'] = self::$_watcher;
        $_SESSION['flag'] = self::$_flag;
        $_SESSION['deny'] = self::$_deny;
        $_SESSION['tempo'] = self::$_tempo;
    }

    public static function compute()
    {
        if (isset(self::$_tempo['time'])) {
            if (time() - self::$_tempo['time'] > 60 * 5) {
                unset(self::$_tempo['time']);
                unset(self::$_tempo['flags']);
            } else {
                self::$_watcher += self::$_tempo['flags'] / 3 > 1 ? intval(round(self::$_tempo['flags'] / 3)) : 0;
                self::$_tempo['flags'] = intval(round(self::$_tempo['flags'])) / 3 > 1 ? self::$_tempo['flags'] % 3 : self::$_tempo['flags'];
            }
        }
        self::$_flag += self::$_watcher / 3 > 1 ? intval(round(self::$_watcher / 3)) : 0;
        self::$_watcher = self::$_watcher / 3 > 1 ? self::$_watcher % 3 : self::$_watcher;

        self::$_deny = self::$_flag >= 5 ? 1 : 0;

        self::save();
        if (self::$_deny) {
            echo 'Inquisit\'d';
            die;
        }
    }

    public static function add_tmp()
    {
        self::$_tempo['time'] = time();
        self::$_tempo['flags'] = isset(self::$_tempo['flags']) ? self::$_tempo['flags'] + 1 : 1;
        sleep(1);
        self::compute();
    }

    public static function add_watcher()
    {
        self::$_watcher++;
        self::compute();
    }

    public static function add_flag()
    {
        self::$_flag++;
        self::compute();
    }

    public static function add_deny()
    {
        self::$_deny++;
        self::compute();
    }
}
