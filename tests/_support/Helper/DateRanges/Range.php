<?php

namespace Tests\Helper\DateRanges;


use Codeception\Example;
use DateRange\Services\Range\Range as RangeInterface;

class Range implements RangeInterface
{
    /** @var Example */
    private $data;

    /**
     * Range constructor.
     * @param Example $row
     */
    public function __construct(Example $row)
    {
        $this->data = $row;
    }

    /**
     * @return string Start date (Y-m-d)
     */
    public function start(): string
    {
        return $this->data['start'];
    }

    /**
     * @return string End date (Y-m-d)
     */
    public function end(): string
    {
        return $this->data['end'];
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return $this->data['price'];
    }
}