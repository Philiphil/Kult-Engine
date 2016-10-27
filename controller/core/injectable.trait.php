<?php

namespace kult_engine;

trait injectable
{
    public static function inject($fnord)
    {
        foreach ($fnord as $param => $value) {
            static::$$param = $value;
        }
    }

    public static function inject_default_state()
    {
        // Needs to be overide in order to work with static properties
        $reflection = new \ReflectionClass(get_called_class());
        $fnord = $reflection->getDefaultProperties();
        foreach ($fnord as $param => $value) {
            static::$$param = $value;
        }
    }
}
