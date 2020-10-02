<?php

namespace KultEngine\Core\Dao;
use KultEngine\Core\Dao\DaoableProperty;

class Id extends DaoableProperty{
    public int $type=1;
    public bool $isNullable=false;
    public $defaultValue=null;
}