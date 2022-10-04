<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Kult Engine
 * @author Théo Sorriaux (philiphil)
 * @copyright Copyright (c) 2016-2222, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine\Core\DynamoDb\Converter;

use KultEngine\Core\DynamoDb\DynamoItem;
use KultEngine\Core\DynamoDb\DynamoItemProxy;
use KultEngine\Core\DynamoDb\Table\AbstractTable;

class DynamoArrayConverter
{
    public static function fromDynamoItemProxy(DynamoItemProxy $dynamoItemProxy, DynamoItem $dynamoItem, AbstractTable $table): DynamoItem
    {
        $reflection = new \ReflectionObject($dynamoItemProxy);
        $diReflection = new \ReflectionObject($dynamoItem);
        foreach ($reflection->getProperties() as $property) {
            try {
                $diProperty = $diReflection->getProperty($property->getName());
                switch ($diProperty->getType()->getName()) {
                    case 'string':
                        $dynamoItem->{$property->getName()} = $dynamoItemProxy->{$property->getName()};
                        break;
                    case 'int':
                        $dynamoItem->{$property->getName()} = (int) $dynamoItemProxy->{$property->getName()};
                        break;
                    case 'float':
                        $dynamoItem->{$property->getName()} = (float) $dynamoItemProxy->{$property->getName()};
                        break;
                    case 'bool':
                        $dynamoItem->{$property->getName()} = (bool) $dynamoItemProxy->{$property->getName()};
                        break;
                    case 'array':
                        $dynamoItem->{$property->getName()} = (array) $dynamoItemProxy->{$property->getName()};
                        break;
                    default: //object
                        //fonctionne pour cellId
                        $dynamoItem->{$property->getName()} = new ($diProperty->getType()->getName())($dynamoItemProxy->{$property->getName()});
                }
            } catch (\ReflectionException $e) { //dynamic property
                if ($property->getName() == $table->getPartKey()) {
                    $dynamoItem->setPartKey($dynamoItemProxy->{$property->getName()});
                } elseif ($property->getName() == $table->getSortKey()) {
                    $dynamoItem->setSortKey($dynamoItemProxy->{$property->getName()});
                } else {
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
        if ($attributeValue->getN() !== null) {//faire diff etnre int et float
            $n = $attributeValue->getN();

            return ctype_digit($n) && 0 !== mb_strlen($n) ? (int) $n : (float) $n;
        }
        if ($attributeValue->getBool() !== null) {
            return (bool) $attributeValue->getBool();
        }
        if (count($attributeValue->getM()) > 0) {
            $array = [];
            foreach ($attributeValue->getM() as $name => $element) {
                $array[$name] = self::fromAttributeValue($element);
            }

            return $array;
        }
        if (count($attributeValue->getL()) > 0) {
            $array = [];
            foreach ($attributeValue->getL() as $element) {
                $array[] = self::fromAttributeValue($element);
            }

            return $array;
        }

        return null;
    }
}
