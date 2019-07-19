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
     * @param Collection $ranges
     * @throws Exception
     */
    public function insert(Collection $ranges): void
    {
        $this->dbConnection->query(sprintf(
            'INSERT INTO `ranges` (`id`, `start`, `end`, `price`) VALUES %s',
            (string)$ranges
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
                    (string)$ranges
                )
            );
        }
    }

    /**
     * @param Collection $ranges
     * @throws Exception
     */
    public function deleteList(Collection $ranges)
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
     * @param Range $range
     * @throws Exception
     */
    public function delete(Range $range): void
    {
        $this->dbConnection->query(
            sprintf(
                'DELETE FROM `ranges` WHERE `start` = "%s" AND `end` = "%s"',
                $range->start(),
                $range->end()
            )
        );
    }

    /**
     * @throws Exception
     */
    public function deleteAll(): void
    {
        $this->dbConnection->query('DELETE FROM `ranges`');
    }

    /**
     * @param DbRange $range
     * @return Collection
     * @throws Exception
     */
    public function listOfAffected(DbRange $range): Collection
    {
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
     * @return DbRange
     * @throws Exception
     */
    private function entity(Row $row): DbRange
    {
        return new DbRange(
            $row->get(self::START),
            $row->get(self::END),
            $row->get(self::PRICE),
            $row->get(self::ID)
        );
    }
}