<?php

namespace KultEngine\Core\DynamoDb\Converter;

use KultEngine\Core\DynamoDb\DynamoItem;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;


class DynamoItemConverter
{

	public static function objectToDynamoArray(mixed $object): array
	{
		$item = [];
		$reflection = new \ReflectionObject($object);
		foreach($reflection->getProperties() as $property){

			if($property->isStatic() || !$property->isPublic() || $object->{$property->getName()} === NULL) continue;
            try{
                $item[$property->getName()] =
                    self::propertyToAttributeValue($object->{$property->getName()});
            }catch (\Exception $e){
                //var_dump($property->getName());
                //var_dump(gettype($object->{$property->getName()}));
                continue;
            }
		}

		return $item;
	}

	private static function propertyToAttributeValue(mixed $property): AttributeValue{
		switch (gettype($property)) {
			case "integer":
			case "double":
                return new AttributeValue(['N' => (string) $property ]);
			case "string":
                return new AttributeValue(['S' => (string) $property]);
			case "boolean":
                return new AttributeValue(['BOOL' =>  $property]);
			case "array":
				return self::arrayToAttributeValue($property);
            default:
                return self::complexTypeToAttributeValue($property);
		}
	}

	private static function arrayToAttributeValue(array $property): AttributeValue{
		$array = [];
		if(isset( $property[0]) && isset( $property[count($property)-1] )){//is array
			foreach( $property as $item){
			    if($item !== NULL) $array[] = self::propertyToAttributeValue($item);

			}
			return new AttributeValue(['L' => $array]);
		}
		//is map
		foreach( $property as $key => $item){
            if($item !== NULL) $array[$key] = self::propertyToAttributeValue($item);
		}
		return new AttributeValue(['M' => $array]);
	}

	private static function complexTypeToAttributeValue(mixed $object): AttributeValue
	{
	    $reflection = new \ReflectionClass($object);

        //if it is a doctrine entity then we'll only get the id
        try{
            $method = $reflection->getMethod("getId");
            if($method->isPublic()){
                return  self::propertyToAttributeValue($object->getId());
            }
        }catch (\ReflectionException $e){
            //no getId method, not a doctrine entity
            //it might be a struct-like object and if it is it might have an id
            //if we have an id, we'll call it a day
            foreach($reflection->getProperties() as $property){
                if($property->isStatic() ||$property->isPrivate() || $object->{$property->getName()} === null) continue;
                if($property->getName() == "id") return  self::propertyToAttributeValue($object->id);
            }

        }

        //TODO: handle other complex types
        //such as DateTime, DateInterval, etc

        //then it must be a struct-like object
        return new AttributeValue(['M' => self::objectToDynamoArray($object)]);

	}
}
