<?php

namespace kult_engine;

trait injectable
{
    public static function inject($dep)
    {
        foreach ($dep as $param => $value) {
            eval(get_called_class().'::$'.$param.'=$value;');
        }
    }
}
