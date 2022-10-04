<?php

namespace KultEngine\Core\DynamoDb;

interface DynamoItem
{
    public function getPartKey(): string;
    public function getSortKey(): string;
    public function setPartKey(string $partKey): DynamoItem;
    public function setSortKey(string $sortKey): DynamoItem;
	public function denormalize(): void;
}
