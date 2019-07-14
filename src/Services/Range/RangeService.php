<?php

namespace DateRange\Services\Range;

use DateRange\Core\Database;

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
}