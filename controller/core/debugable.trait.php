<?php

namespace kult_engine;

trait debugable
{
    public static function debug()
    {
        echo '<br>DEBUG :: '.get_called_class().'<br>';
        $reflection = new \ReflectionClass(get_called_class());
        $vars = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
        $_vars = [];
        foreach ($vars as $var) {
            array_push($_vars, $var->name);
        }
        $vars = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach ($vars as $var) {
            array_push($_vars, $var->name);
        }
        foreach (get_class_vars(get_called_class()) as $key => $value) {
            if (!in_array($key, $_vars)) {
                echo $key.'->';
                $bfr = is_array($value) || is_object($value) ? $value : htmlentities($value);
                var_dump($bfr);
                echo '<br>';
            }
        }
        echo 'END<br>';
    }
}
