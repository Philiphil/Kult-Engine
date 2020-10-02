<?php

namespace KultEngine\Core\Dao;
use KultEngine\DaoableObject;

class DaoableProperty{
    public int $type=1;
    public bool $isNullable=false;
    public $defaultValue=null;

    const TYPE_ID=1;
    const TYPE_DATETIME=2;
    const TYPE_FOREIGN_KEY=3;
    const TYPE_INT=4;
    const TYPE_STRING=5;
    const TYPE_DOUBLE=6;
    const TYPE_BOOL=7;    
    const TYPE_LONGTEXT=8;
    const TYPE_BLOB=9;

    public function setType(string $nameType){
        //$nameType=substr($nameType, strrpos($nameType, '\\')+1);
        switch ($nameType) {
            case 'KultEngine\Core\Dao\Id':
                $this->type = self::TYPE_ID;
                break;
            case 'DateTime':
            case '\DateTime':
                $this->type = self::TYPE_DATETIME;
                break;
            case 'int':
                $this->type = self::TYPE_INT;
                break;
            case 'string':          
                $this->type = self::TYPE_STRING;
                break;
            case 'double':
            case 'real':
            case 'float':
                $this->type = self::TYPE_DOUBLE;
                break;
            case 'bool':
                $this->type = self::TYPE_BOOL;
                break;
           /* case 'KultEngine\Core\Dao\Blob'://not created
                $this->type = self::TYPE_BLOB;
                break;*/
            default:
                $this->type = self::TYPE_LONGTEXT;
                break;
        }
    }    
}
