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
    private const ID    = 'id';
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
    public function list(): Collection
    {
        return $this->collection($this->dbConnection->query('SELECT * FROM `ranges` ORDER BY `start`'));
    }

    /**
     * @param Row $row
     * @throws Exception
     */
    public function insert(Row $row): void
    {
        // TODO: reimplement to use collections
        $this->dbConnection->query(sprintf(
            'INSERT INTO `ranges` (`start`, `end`, `price`) VALUES ("%s", "%s", %f)',
            $row->get(self::START),
            $row->get(self::END),
            $row->get(self::PRICE)
            ));
    }

    /**
     * @param Collection $ranges
     * @throws Exception
     */
    public function save(Collection $ranges)
    {
        if ($ranges->count()) {
            $this->dbConnection->query(
                sprintf(
                    'INSERT INTO `ranges` (`id`, `start`, `end`, `price`) VALUES %s
                            ON DUPLICATE KEY UPDATE `start` = VALUES(`start`), `end` = VALUES(`end`), `price` = VALUES(`price`)',
                    implode(
                        ', ',
                        $ranges->map(function (Range $range) {
                            return new RangeSqlValue($range);
                        })->column('value')
                    )
                )
            );
        }
    }

    /**
     * @param Collection $ranges
     * @throws Exception
     */
    public function delete(Collection $ranges)
    {
        if ($ranges->count()) {
            $this->dbConnection->query(
                sprintf(
                    'DELETE FROM `ranges` WHERE `id` IN (%s)',
                    implode(', ', $ranges->column('identifier'))
                )
            );
        }
    }

    /**
     * @param Row $row
     * @return Collection
     * @throws Exception
     */
    public function listOfAffected(Row $row): Collection
    {
        $range = $this->entity($row);
        return $this->collection(
            $this->dbConnection->query(
                sprintf(
                    'SELECT * FROM `ranges` WHERE `start` <= "%s" AND `end` >= "%s" ORDER BY `start`',
                    $range->nextDay(),
                    $range->previousDay()
                )
            )
        );
    }

    /**
     * @param array $data
     * @return Collection
     * @throws Exception
     */
    private function collection(array $data): Collection
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
     * @throws Exception
     */
    private function entity(Row $row): Range
    {
        if ($row->get(self::START) > $row->get(self::END)) {
            throw new Exception('Start should be before end');
        }
        return new Range(
            $row->get(self::START),
            $row->get(self::END),
            $row->get(self::PRICE),
            $row->get(self::ID)
        );
    }
}