<?php

namespace kult_engine;

trait queryable
{
    public static function query($fnord)
    {
        return connector::query($fnord);
    }
}
