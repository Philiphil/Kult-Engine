<?php

namespace KultEngine\Core\DynamoDb\Converter;
use KultEngine\Core\DynamoDb\DynamoItemProxy;
use KultEngine\Core\DynamoDb\DynamoItem;
use KultEngine\Core\DynamoDb\Table\AbstractTable;

class DynamoArrayConverter
{

    public static function fromDynamoItemProxy(DynamoItemProxy $dynamoItemProxy, DynamoItem $dynamoItem, AbstractTable $table) : DynamoItem
    {
        $reflection = new \ReflectionObject($dynamoItemProxy);
        $diReflection = new \ReflectionObject($dynamoItem);
        foreach($reflection->getProperties() as $property){
            try{
                $diProperty = $diReflection->getProperty($property->getName());
                switch ($diProperty->getType()->getName()){
                    case 'string':
                        $dynamoItem->{$property->getName()} = $dynamoItemProxy->{$property->getName()};
                        break;
                    case 'int':
                        $dynamoItem->{$property->getName()} = (int)$dynamoItemProxy->{$property->getName()};
                        break;
                    case 'float':
                        $dynamoItem->{$property->getName()} = (float)$dynamoItemProxy->{$property->getName()};
                        break;
                    case 'bool':
                        $dynamoItem->{$property->getName()} = (bool)$dynamoItemProxy->{$property->getName()};
                        break;
                    case 'array':
                        $dynamoItem->{$property->getName()} = (array) $dynamoItemProxy->{$property->getName()};
                        break;
                    default: //object
                        //fonctionne pour cellId
                        $dynamoItem->{$property->getName()} = new ($diProperty->getType()->getName())( $dynamoItemProxy->{$property->getName()});
                }
            }catch (\ReflectionException $e){ //dynamic property
                if($property->getName() == $table->getPartKey()){
                    $dynamoItem->setPartKey($dynamoItemProxy->{$property->getName()});
                }else if($property->getName() == $table->getSortKey()) {
                    $dynamoItem->setSortKey($dynamoItemProxy->{$property->getName()});
                }else {
                    $dynamoItem->{$property->getName()} = $dynamoItemProxy->{$property->getName()};
                }
            }
        }
        $dynamoItem->denormalize();
        return $dynamoItem;
    }

    public static function fromDynamoArray(array $dynamoArray): DynamoItemProxy
    {
        $object = new DynamoItemProxy();
        foreach ($dynamoArray as $name => $value) {
            $convertedValue = self::fromAttributeValue($value);
            $object->{$name} = $convertedValue;
        }
        return $object;
    }

    public static function fromAttributeValue(AttributeValue $attributeValue): mixed
    {
        if ($attributeValue->getS() !== null) {
            return (string) $attributeValue->getS();
        }
        if($attributeValue->getN() !== null) {//faire diff etnre int et float
            $n = $attributeValue->getN();
            return ctype_digit($n) && 0 !== mb_strlen($n) ? (int) $n : (float) $n;
        }
        if($attributeValue->getBool() !== null) {
            return (bool) $attributeValue->getBool();
        }
        if( count($attributeValue->getM()) >0 ) {
            $array=[];
            foreach ($attributeValue->getM() as $name => $element) {
                $array[$name] = self::fromAttributeValue($element);
            }
            return $array;
        }
        if( count($attributeValue->getL()) >0 ) {
            $array=[];
            foreach ($attributeValue->getL() as $element) {
                $array[] = self::fromAttributeValue($element);
            }
            return $array;
        }
        return null;
    }
}
