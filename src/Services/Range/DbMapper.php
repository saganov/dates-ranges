<?php

namespace DateRange\Services\Range;

use DateRange\Core\Collection;
use DateRange\Core\Database;
use DateRange\Core\Row;

class DbMapper
{
    private const START = 'start';
    private const END   = 'end';
    private const PRICE = 'price';
    /** @var Database */
    private $dbConnection;

    public function __construct(Database $connection)
    {
        $this->dbConnection = $connection;
    }

    public function list()
    {
        return $this->collection(
            [
                [
                    'start' => 'Y-m-d',
                    'end' => 'Y-m-d',
                    'price' => 15
                ],
                [
                    'start' => 'Y-m-d',
                    'end' => 'Y-m-d',
                    'price' => 25
                ]
            ]
        );
        //return $this->dbConnection->query('SELECT * FROM ranges');
    }

    private function collection(array $data)
    {
        $collection = new Collection();
        foreach ($data as $row) {
            $collection->push($this->entity(new Row($row)));
        }
        return $collection;
    }

    private function entity(Row $row)
    {
        return new Range($row->get(self::START), $row->get(self::END), $row->get(self::PRICE));
    }
}