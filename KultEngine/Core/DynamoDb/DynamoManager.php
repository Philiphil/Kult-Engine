<?php

namespace KultEngine\Core\DynamoDb;

use KultEngine\Core\DynamoDb\Table\AbstractTable;
use AsyncAws\DynamoDb\Input\BatchWriteItemInput;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\DeleteRequest;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\PutRequest;
use AsyncAws\DynamoDb\ValueObject\WriteRequest;
use AsyncAws\DynamoDb\DynamoDbClient;
use KultEngine\Core\DynamoDb\Converter\DynamoConverter;
use Exception;

class DynamoManager
{
    private ?AbstractTable $table=null;

    public function __construct(
        private DynamoDbClient $client,
    ) {
    }

    public function setTable(?AbstractTable $table): void{
        $this->table = $table ??  $this->table;
    }

    /**
     * @throws Exception
     */
    public function getTable(): AbstractTable{
        if(!$this->table){
            throw new Exception('Table not set');
        }
        return $this->table;
    }

    public function toDynamoArray(DynamoItem $obj): array
    {
        return array_merge(DynamoConverter::toDynamoArray($obj), $this->getDynamoKeys($obj));
    }

    public function getDynamoKeys(DynamoItem $obj): array
    {
        return [
            $this->getTable()->getPartKey() => new AttributeValue(['S' =>  $obj->getPartKey()]),
            $this->getTable()->getSortKey() => new AttributeValue(['S' =>  $obj->getSortKey()]),
        ];
    }

    public function persist(DynamoItem $obj, ?AbstractTable $table=null)
    {
        $this->persistCollection([$obj],$table);
    }

    /*25 per 25 restriction*/
    public const BATCHLIMIT=1000*1000*16;//16mb

    public function persistCollection(array $obj, ?AbstractTable $table=null)
    {
        $this->setTable($table);
        $putRequests = [];
        foreach ($obj as $item) {
            $putRequests[count($putRequests)] = new WriteRequest(['PutRequest' => new PutRequest(['Item' =>  $this->toDynamoArray($item)])]);

            if(count($putRequests)==25 ){
                $this->batchWriteItem($putRequests);
            }else if(strlen(json_encode($putRequests)) >= $this::BATCHLIMIT-(1000*1000)){
                //16mb -1 (safety zone)
                $lastone = array_pop($putRequests);
                $this->batchWriteItem($putRequests);
                $putRequests = [$lastone];
            }
        }
        if(count($putRequests)>0){
            $this->batchWriteItem($putRequests);
        }
    }

    private function batchWriteItem(array $putRequests): void{
        usleep(300 * 1000); // 250ms
        $item = $this->client->batchWriteItem([
            'RequestItems' => [
                $this->getTable()->getName() => $putRequests,
            ],
            'ReturnConsumedCapacity'=>ReturnConsumedCapacity::NONE
        ]);
        $unprocessed = $item->getUnprocessedItems();
        if(count($item->getUnprocessedItems())) $this->batchWriteItem($unprocessed[$this->getTable()->getName()]);
    }


    public function findByPartKey(string $partKey,  DynamoItem $class,?AbstractTable $table=null): array
    {
        $this->setTable($table);
        $result = $this->client->query(new QueryInput([
            'TableName'                 => $this->getTable()->getName(),
            'KeyConditionExpression'    =>  $this->getTable()->getPartKey() .' = :partKey',
            'ExpressionAttributeValues' => [
                ':partKey' => new AttributeValue(['S' => $partKey]),
            ],
        ]));

        return $this->deserializeResult($result->getItems(), $class);
    }

    public function findByPartAndSortKey(string $partKey, string $sortKey, DynamoItem $class, ?AbstractTable $table=null)
    {
        $this->setTable($table);

        $result = $this->client->query(new QueryInput([
            'TableName'                 => $this->getTable()->getName(),
            'KeyConditionExpression'    =>
                $this->getTable()->getPartKey() .' = :partKey AND '.$this->getTable()->getSortKey().' = :sortKey',
            'ExpressionAttributeValues' => [
                ':partKey' => new AttributeValue(['S' => $partKey]),
                ':sortKey' => new AttributeValue(['S' => $sortKey]),
            ],
        ]));
        return $this->deserializeResult($result->getItems(), $class);
    }

    public function findByPartKeyAndBeginsWithSortKey(string $partKey, string $sortKey, DynamoItem $class, ?AbstractTable $table=null)
    {
        $this->setTable($table);

        $result = $this->client->query(new QueryInput([
            'TableName'                 => $this->getTable()->getName(),
            'KeyConditionExpression'    =>
                $this->getTable()->getPartKey() .' = :partKey AND begins_with('.$this->getTable()->getSortKey().', :sortKey)',
            'ExpressionAttributeValues' => [
                ':partKey' => new AttributeValue(['S' => $partKey]),
                ':sortKey' => new AttributeValue(['S' => $sortKey]),
            ],
        ]));
        return $this->deserializeResult($result->getItems(), $class);
    }

    public function findByBeginsWithPartKeyAndSortKeyContains(string $partKey, string $sortKey,  DynamoItem $class, ?AbstractTable $table=null)
    {
        $this->setTable($table);

        $result = $this->client->scan(new ScanInput([
            'TableName'                 => $this->getTable()->getName(),
            'FilterExpression'    =>
                'begins_with('.$this->getTable()->getPartKey().', :partKey) AND contains('.$this->getTable()->getSortKey().', :sortKey)',
            'ExpressionAttributeValues' => [
                ':partKey' => new AttributeValue(['S' => $partKey]),
                ':sortKey' => new AttributeValue(['S' => $sortKey]),
            ],
        ]));
        return $this->deserializeResult($result->getItems(), $class);
    }

    public function deserializeResult(\Generator $result, DynamoItem $class): array
    {
        $objects = [];
        foreach ($result as $item) {
            $objects[] = DynamoConverter::fromDynamoItemProxy(
                DynamoConverter::fromDynamoArray($item), new $class(), $this->getTable()
            );
        }
        return $objects;
    }

    public function remove(DynamoItem $obj, ?AbstractTable $table=null)
    {
        $this->removeCollection([$obj],$table);
    }

    public function removeCollection(array $obj, ?AbstractTable $table=null)
    {
        $this->setTable($table);
        $putRequests = [];
        $i=0;
        foreach ($obj as $item) {
            $i++;
            $putRequests[] = new WriteRequest(
                [
                    'DeleteRequest' => new DeleteRequest(['Key' =>
                        [
                            $this->getTable()->getPartKey() => new AttributeValue(['S' => $item->getPartKey()]),
                            $this->getTable()->getSortKey()=> new AttributeValue(['S' => $item->getSortKey()]),
                        ]
                    ])
                ]
            );
            if($i%25==0){
                $this->client->batchWriteItem([
                    'RequestItems' => [
                        $this->getTable()->getName() => $putRequests,
                    ],
                ]);
                $putRequests = [];
            }
        }
        if(count($putRequests)>0){
            $this->client->batchWriteItem([
                'RequestItems' => [
                    $this->getTable()->getName() => $putRequests,
                ],
            ]);
        }
    }
}
