<?php

namespace DateRange\Services\Range;

class DbRangeBuilder
{
    /**
     * @var Range
     */
    private $range;

    public function __construct(Range $range)
    {
        $this->range = $range;
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
            $this->range->identifier()
        );
    }
}