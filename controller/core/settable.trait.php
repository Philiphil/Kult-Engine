<?php

namespace kult_engine;

trait settable
{
    private static $set = 0;

    public static function init()
    {
        if (!static::$set) {
            static::$set = 1;
            static::setter();

            return 0;
        }
        trigger_error(get_called_class().' ALREADY SET');

        return 1;
    }

    public static function init_required()
    {
        if (!static::$set) {
            trigger_error(get_called_class().' NOT SET', E_USER_ERROR);

            return 0;
        }

        return 1;
    }
}
