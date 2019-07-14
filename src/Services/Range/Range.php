<?php

namespace DateRange\Services\Range;

use DateRange\Core\ArraySerializable;

class Range implements ArraySerializable
{
    private const START = 'start';
    private const END   = 'end';
    private const PRICE = 'price';
    /** @var string  */
    private $start;
    /** @var string  */
    private $end;
    /** @var float  */
    private $price;

    /**
     * Range constructor.
     * @param string $start
     * @param string $end
     * @param float $price
     */
    public function __construct(string $start, string $end, float $price)
    {
        $this->start = $start;
        $this->end = $end;
        $this->price = $price;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::START => $this->start,
            self::END   => $this->end,
            self::PRICE => $this->price,
        ];
    }
}