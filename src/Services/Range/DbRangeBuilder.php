<?php

namespace DateRange\Services\Range;

class DbRangeBuilder
{
    /**
     * @var Range
     */
    private $range;
    /** @var int */
    private $dbKey;

    public function __construct(Range $range, int $dbKey = null)
    {
        $this->range = $range;
        $this->dbKey = $dbKey;
    }

    /**
     * @return DbRange
     */
    public function build(): DbRange
    {
        return new DbRange(
            $this->range->start(),
            $this->range->end(),
            $this->range->price(),
            $this->dbKey
        );
    }
}