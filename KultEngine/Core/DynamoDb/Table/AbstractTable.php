<?php


namespace KultEngine\Core\DynamoDb\Table;


abstract class AbstractTable
{
    abstract public function getName(): string;
    abstract public function getPartKey(): string;
    abstract public function getSortKey(): string;
    abstract public function getBillingMode(): string;
    static string $billingMode_ppr = 'PAY_PER_REQUEST';
    static string $billingMode_provisioned = 'PROVISIONED';

}
