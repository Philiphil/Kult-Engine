<?php

namespace KultEngine\Core\DynamoDb;

use App\Domain\Poi\Model\ConcretePoi;
use App\Domain\Poi\Model\Position;

class AbstractDynamoItem implements DynamoItem
{

    public function getPartKey(): string{
        return "not_set";
    }

    public function getSortKey(): string
    {
        return  realUniqid();
    }

    public function denormalize(): void
    { }

    public function setPartKey(string $partKey): DynamoItem
    {
        return $this;
    }

    public function setSortKey(string $sortKey): DynamoItem
    {
        return $this;
    }
}
