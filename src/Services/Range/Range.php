<?php

namespace DateRange\Services\Range;

interface Range
{
    /**
     * @return string Start date (Y-m-d)
     */
    public function start(): string;

    /**
     * @return string End date (Y-m-d)
     */
    public function end(): string;

    /**
     * @return float
     */
    public function price(): float;
}