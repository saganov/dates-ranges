<?php

namespace DateRange\Services\Range;

use DateRange\Core\Collection;
use DateRange\Core\Database;
use DateRange\Core\Row;
use Exception;

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

    /**
     * @return Collection
     * @throws Exception
     */
    public function list()
    {
        return $this->collection($this->dbConnection->query('SELECT * FROM ranges'));
    }

    /**
     * @param array $data
     * @return Collection
     */
    private function collection(array $data)
    {
        $collection = new Collection();
        foreach ($data as $row) {
            $collection->push($this->entity(new Row($row)));
        }
        return $collection;
    }

    /**
     * @param Row $row
     * @return Range
     */
    private function entity(Row $row)
    {
        return new Range($row->get(self::START), $row->get(self::END), $row->get(self::PRICE));
    }
}