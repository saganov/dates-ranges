<?php

namespace DateRange\Services\Range;

use DateRange\Core\Collection;
use DateRange\Core\Database;
use Exception;

class RangeService
{
    /** @var Database */
    private $dbConnection;

    /** @var DbMapper */
    private $mapper;

    /**
     * RangeService constructor.
     * @param Database $connection
     */
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
        return $this->mapper()->list();
    }

    /**
     * @param Range $range
     * @throws Exception
     */
    public function save(Range $range)
    {
        $toCreate = new Collection();
        $toDelete = new Collection();
        $toUpdate = new Collection();
        $master = (new DbRangeBuilder($range))->build();
        $this->mapper()->listOfAffected($master)->walk(function (DbRange $affectedRange, $key) use (&$master, $toCreate, $toDelete, $toUpdate) {
            switch ($affectedRange->affectMode($master)) {
                case DbRange::AFFECT_MODE_MERGE:
                    $master = $affectedRange->merge($master);
                    $toDelete->push($affectedRange);
                    break;
                case DbRange::AFFECT_MODE_SPLIT:
                    $toCreate->pushArray($affectedRange->split($master));
                    $toDelete->push($affectedRange);
                    break;
                case DbRange::AFFECT_MODE_CUT_TAIL:
                    $toUpdate->push($affectedRange->cutTail($master));
                    break;
                case DbRange::AFFECT_MODE_CUT_HEAD:
                    $toUpdate->push($affectedRange->cutHead($master));
                    break;
                case DbRange::AFFECT_MODE_HIDE:
                    $toDelete->push($affectedRange);
                    break;
                default:
                    break;
            }
        });
        $toCreate->push($master);
        $this->mapper()->deleteList($toDelete);
        $this->mapper()->save($toUpdate);
        $this->mapper()->insert($toCreate);
    }

    /**
     * @param Range|null $range
     * @throws Exception
     */
    public function delete(?Range $range = null): void
    {
        $range ? $this->mapper()->delete($range) : $this->mapper()->deleteAll();
    }

    /**
     * @return DbMapper
     */
    private function mapper(): DbMapper
    {
        if (!$this->mapper) {
            $this->mapper = new DbMapper($this->dbConnection);
        }
        return $this->mapper;
    }
}