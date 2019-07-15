<?php

namespace DateRange\Services\Range;

use DateRange\Core\Database;
use Exception;

class RangeService
{
    /** @var Database */
    private $dbConnection;

    public function __construct(Database $connection)
    {
        $this->dbConnection = $connection;
    }

    public function list()
    {
        return (new DbMapper($this->dbConnection))->list();
    }

    /**
     * @param array $range
     * @throws Exception
     */
    public function save(array $range)
    {
        (new DbMapper($this->dbConnection))->save($range);
    }
}