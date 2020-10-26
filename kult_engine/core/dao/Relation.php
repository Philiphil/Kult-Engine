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

namespace KultEngine;

use KultEngine\Core\Dao\DaoableProperty;
use KultEngine\Core\Dao\Id;

class Relation extends DaoableProperty
{
    const TYPE_MANY_TO_MANY = 1;
    const TYPE_MANY_TO_ONE = 2;
    const TYPE_ONE_TO_MANY = 3;
    const TYPE_ONE_TO_ONE = 4;
    public int $type = 0;

    public KultEngine\DaoableObject $source;
    public KultEngine\DaoableObject $target;

    public ?string $sourceColumn;
    public ?string $targetColumn;

    public function __construct(int $type, DaoableObject $target)
    {
        $this->type = $type;
        $this->target = $target;

        parent::construct();
    }
}
/*
    relation spec
        type de relation : determiner par enfant
        classe source : injecter automatiquement par daogenerator
        classe cible : manuel, constructeur

        table source
            useless
        table cible
            useless
            Je peux (veux pas rendre possible) création de 2 table par projet
            Je peux pas renommer table (et meme si c'est le cas, je peux magouiller pour automatiser le fait que je le sache)

        col cible
            déjà le nom de la prop qui déclare le relation
        col source
            seul info manquante



*/

class OneToOneRelation extends Relation
{
    public int $type = Relation::TYPE_ONE_TO_ONE;

    public function __construct(DaoableObject $target)
    {
        parent::construct($this->type, $target);
    }
}
class ManyToOneRelation extends Relation
{
    public int $type = Relation::TYPE_MANY_TO_ONE;

    public function __construct(DaoableObject $target)
    {
        parent::construct($this->type, $target);
    }
}
class OneToManyRelation extends Relation
{
    public int $type = Relation::TYPE_ONE_TO_MANY;

    public function __construct(DaoableObject $target)
    {
        parent::construct($this->type, $target);
    }
}
class ManyToManyRelation extends Relation
{
    public int $type = Relation::TYPE_MANY_TO_MANY;

    public function __construct(int $type, DaoableObject $target)
    {
        parent::construct($this->type, $target);
    }
}
/*
    OneToOne
        Je dois creer une simple column fk
        stock id
        retourne item
    ManyToOne
        source devrait avoir column fk
        target devrait rien avoir coté sql ?
    OneToMany
        source devrait rien avoir ?
    ManyToMany
        chacun devrait etre informer ?
