<?php

namespace DateRange\Services\Range;

use DateRange\Core\ArraySerializable;
use DateRange\Core\Row;

class RangeSqlValue implements ArraySerializable
{
    /** @var Range  */
    private $origin;
    /**
     * RangeSqlValue constructor.
     * @param Range $range
     */
    public function __construct(Range $range)
    {
        $this->origin = $range;
    }

    public function value()
    {
        $row = new Row($this->toArray());
        return sprintf(
            '(%d, "%s", "%s", %f)',
            $this->origin->identifier(),
            $row->get('start'),
            $row->get('end'),
            $row->get('price')
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->origin->toArray();
    }
}