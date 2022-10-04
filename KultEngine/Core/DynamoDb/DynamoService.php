<?php

namespace KultEngine\Core\DynamoDb;
use App\Domain\Weather\Model\WeatherReport;
use KultEngine\Core\DynamoDb\Table\AbstractTable;
use KultEngine\Core\DynamoDb\Table\Forge;
use AsyncAws\DynamoDb\Input\BatchWriteItemInput;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\DeleteRequest;
use AsyncAws\DynamoDb\ValueObject\PutRequest;
use AsyncAws\DynamoDb\ValueObject\WriteRequest;
use AsyncAws\DynamoDb\DynamoDbClient;
use KultEngine\Core\DynamoDb\Converter\DynamoConverter;
use Exception;

class DynamoService
{
    public function __construct(
        private DynamoManager $privateDynamo,
        private Forge $forge,
    ){
        $this->privateDynamo->setTable($this->forge);
    }

    public function getPrivateDynamoManager(): DynamoManager{
        return $this->privateDynamo;
    }
}
