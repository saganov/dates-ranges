<?php

namespace DateRange\Services\Range;

use DateRange\Core\Collection;
use DateRange\Core\Database;
use DateRange\Core\Row;
use Exception;

class RangeService
{
    /** @var Database */
    private $dbConnection;

    /** @var DbMapper */
    private $mapper;

    public function __construct(Database $connection)
    {
        $this->dbConnection = $connection;
    }

    public function list()
    {
        return $this->mapper()->list();
    }

    /**
     * @param array $range
     * @throws Exception
     */
    public function save(array $range)
    {
        $toCreate = new Collection();
        $toDelete = new Collection();
        $toUpdate = new Collection();
        $row = new Row($range);
        // TODO: Make the shared builder
        $master = new Range($row->get('start'), $row->get('end'), $row->get('price'));
        $this->mapper()->listOfAffected($row)->walk(function (Range $affectedRange, $key) use (&$master, &$toCreate, &$toDelete, &$toUpdate) {
            switch ($affectedRange->affectMode($master)) {
                case Range::AFFECT_MODE_MERGE:
                    $master = $affectedRange->merge($master);
                    $toDelete->push($affectedRange);
                    break;
                case Range::AFFECT_MODE_SPLIT:
                    $splitted = $affectedRange->split($master);
                    $toCreate->push($splitted[0]);
                    $toCreate->push($splitted[1]);
                    $toDelete->push($affectedRange);
                    break;
                case Range::AFFECT_MODE_CUT_TAIL:
                    $toUpdate->push($affectedRange->cutTail($master));
                    break;
                case Range::AFFECT_MODE_CUT_HEAD:
                    $toUpdate->push($affectedRange->cutHead($master));
                    break;
                case Range::AFFECT_MODE_HIDE:
                    $toDelete->push($affectedRange);
                    break;
                default:
                    break;
            }
        });
        $toCreate->push($master);
        $this->mapper()->delete($toDelete);
        $this->mapper()->save($toUpdate);
        $this->mapper()->save($toCreate);
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