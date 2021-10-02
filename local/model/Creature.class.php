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
 * @copyright Copyright (c) 2016-2020, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

use  KultEngine\Core\Dao\Relation;

/*
 *
 * Demo for Dao/Model/Relation
 */
class BaseCreature extends KultEngine\Core\Dao\DaoableObject
{
    #[Relation\ManyToOne]
    public Race $race;
    #[Relation\ManyToOne(Relation\Cascade::PERSIST)]
    public Attributes $attributes;
    #[Relation\ManyToOne(Relation\Cascade::PERSIST)]
    public Skills $skills;
    #[Relation\ManyToOne(Relation\Cascade::PERSIST)]
    public EquipmentSlots $equipmentSlots;
    #[Relation\OneToMany]
    public array $items;

    public float $hp = 0;

    public function getMaxHp(): float
    {
        return $this->attributes->getLevel(Attributes::_CONSTITUTION) * 10 + $this->race->hp;
    }

    public float $mp = 0;

    public function getMaxMp(): float
    {
        return $this->attributes->getLevel(Attributes::_INTELLIGENCE) * 10 + $this->race->mp;
    }

    public float $sp = 0;

    public function getMaxSp(): float
    {
        return $this->attributes->getLevel(Attributes::_WISDOM) * 10 -
            $this->attributes->getLevel(Attributes::_INTELLIGENCE) * 10 + $this->race->sp;
    }

    public function getInventory(): float
    {
        $i = 0;
        foreach ($this->items as $item) {
            $i += $item->getWeight();
        }

        return $i;
    }

    public function getMaxInventory(): float
    {
        return $this->attributes->getLevel(Attributes::_STRENGTH) * 10 + $this->race->inventory;
    }
}
class Race extends KultEngine\Core\Dao\DaoableObject
{
    public const _HUMAN = 0;
    public int $type = 0;
    public float $hp = 50;
    public float $mp = 50;
    public float $sp = 50;
    public float $inventory = 10;
    public string $name;
    public string $description;
}

class Attributes extends KultEngine\Core\Dao\DaoableObject
{
    public const _STRENGTH = 0;
    public const _DEXTERITY = 1;
    public const _CONSTITUTION = 2;
    public const _PERCEPTION = 3;
    public const _INTELLIGENCE = 4;
    public const _WISDOM = 5;
    public const _CHARISMA = 6;
    public const _LUCK = 7;
    public const __TOTAL = 8;
    public array $attributes = [];

    public function construct()
    {
        for ($i = 0; $i < self::__TOTAL; $i++) {
            $this->attributes[$i] = 0;
        }
    }

    public function getLevel(int $type): int
    {
        return $this->attributes[$type] ?? 0;
    }
}
class Skills extends KultEngine\Core\Dao\DaoableObject
{
    public const _ENGLISH = 0;
    public const _SPANISH = 1;
    public const _RUSSIAN = 2;
    public const _LATIN = 3;
    public const _CONSPIRACY = 4;
    public const _ESOTERISM = 5;
    public const _VOODOO = 6;
    public const _SPIRIT = 7;
    public const _MEDECINE = 8;
    public const _DISGUISE = 9;
    public const _STEALTH = 10;
    public const _FIREARM = 11;
    public const _DIPLOMACY = 12;
    public const _INTIMIDATION = 13;
    public const _SURVIVAL = 14;
    public const _SPORTINESS = 15;
    public const _ENGINEERING = 16;
    public const __TOTAL = 17;
    public array $skills = [];

    public function construct()
    {
        for ($i = 0; $i < self::__TOTAL; $i++) {
            $this->skills[$i] = 0;
        }
    }

    public function getLevel(int $skill): int
    {
        return $this->skills[$skill] ?? 0;
    }

    public function getAttribute(int $skill): int
    {
        switch ($skill) {
            case self::_ENGLISH:
            case self::_LATIN:
            case self::_SPANISH:
            case self::_RUSSIAN:
            case self::_INTIMIDATION:
            case self::_DIPLOMACY:
                return Attributes::_CHARISMA;
            case self::_SPORTINESS:
                return Attributes::_STRENGTH;
            case self::_CONSPIRACY:
            case self::_ESOTERISM:
            case self::_VOODOO:
            case self::_SPIRIT:
            case self::_MEDECINE:
            case self::_ENGINEERING:
                return Attributes::_INTELLIGENCE;
            case self::_STEALTH:
            case self::_FIREARM:
                return Attributes::_DEXTERITY;
            case self::_SURVIVAL:
                return Attributes::_WISDOM;
            case self::_DISGUISE:
                return Attributes::_PERCEPTION;
            default:
                return Attributes::_LUCK;
        }
    }
}
class EquipmentSlots extends KultEngine\Core\Dao\DaoableObject
{
    public const _HEAD = 0;
    public const _EYES = 1;
    public const _TORSO1 = 2;
    public const _TORSO2 = 3;
    public const _PANTS = 4;
    public const _WEAPON = 5;
    public const _BACK = 6;
    public const __TOTAL = 7;
    public array $slots = [];

    public function construct()
    {
        for ($i = 0; $i < self::__TOTAL; $i++) {
            $this->slots[$i] = null;
        }
    }
}
class Item extends KultEngine\Core\Dao\DaoableObject
{
    public const _FUSIL12 = 0;

    public string $name;
    public string $description;
    public int $type = 0;
    public float $weight = 0.0;

    public bool $usable = false;
    public bool $removeAfterUse = false;
    public float $usageTime = 0.0;

    public bool $equipable = false;
    public ?int $slot = null;
    public bool $stackable = false;
}

class CreatureItem extends KultEngine\Core\Dao\DaoableObject
{
    #[ManyToOne]
    public Item $type;
    #[ManyToOne]
    public BaseCreature $owner;
    public int $count;
    public bool $equiped = false;

    public function getWeight(): float
    {
        return $this->type->weight * $this->count;
    }
}
