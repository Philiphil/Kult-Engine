<?php

namespace KultEngine\Core\DynamoDb\Converter;
use KultEngine\Core\DynamoDb\DynamoItemProxy;
use KultEngine\Core\DynamoDb\DynamoItem;
use KultEngine\Core\DynamoDb\Table\AbstractTable;

class DynamoConverter
{

    public static function toDynamoArray(DynamoItem $object): array
    {
        return DynamoItemConverter::objectToDynamoArray($object);
    }

    public static function fromDynamoItemProxy(DynamoItemProxy $item, DynamoItem $class, AbstractTable $table): DynamoItem
    {
        return DynamoArrayConverter::fromDynamoItemProxy($item, $class, $table);
    }

    public static function fromDynamoArray(array $object): DynamoItemProxy
    {
        return DynamoArrayConverter::fromDynamoArray($object);
    }

}
