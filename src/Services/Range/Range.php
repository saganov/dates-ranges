<?php

namespace DateRange\Services\Range;

use DateInterval;
use DateRange\Core\ArraySerializable;
use DateTime;
use Exception;

class Range implements ArraySerializable
{
    private const START = 'start';
    private const END   = 'end';
    private const PRICE = 'price';
    public const AFFECT_MODE_NONE  = 0;
    public const AFFECT_MODE_MERGE = 1;
    public const AFFECT_MODE_SPLIT = 2;
    public const AFFECT_MODE_CUT_TAIL = 3;
    public const AFFECT_MODE_CUT_HEAD = 4;
    public const AFFECT_MODE_HIDE  = 5;

    /** @var string  */
    private $start;
    /** @var string  */
    private $end;
    /** @var float  */
    private $price;
    /** @var int|null */
    private $id;

    /**
     * Range constructor.
     * @param string $start
     * @param string $end
     * @param float $price
     * @param int|null $id
     */
    public function __construct(string $start, string $end, float $price, int $id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->price = (float)$price;
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function identifier()
    {
        return $this->id;
    }

    public function start()
    {
        return $this->start;
    }

    public function end()
    {
        return $this->end;
    }

    public function price()
    {
        return $this->price;
    }

    /**
     * @return string next day after the range (Y-m-d)
     * @throws Exception
     */
    public function nextDay(): string
    {
        return (new DateTime($this->end))->add(new DateInterval('P1D'))->format('Y-m-d');
    }

    /**
     * @return string previous day before the range (Y-m-d)
     * @throws Exception
     */
    public function previousDay(): string
    {
        return (new DateTime($this->start))->sub(new DateInterval('P1D'))->format('Y-m-d');
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

    /**
     * @param Range $master
     * @return int
     * @throws Exception
     */
    public function affectMode(Range $master)
    {
        if ($this->nextDay() < $master->start() || $this->previousDay() > $master->end()) {
            return self::AFFECT_MODE_NONE;
        } elseif ($this->price === $master->price()) {
            return self::AFFECT_MODE_MERGE;
        } elseif ($this->outOfRange($master)) {
            return self::AFFECT_MODE_NONE;
        } elseif ($this->start < $master->start() && $this->end > $master->end()) {
            return self::AFFECT_MODE_SPLIT;
        } elseif ($this->start < $master->start()) {
            return self::AFFECT_MODE_CUT_TAIL;
        } elseif ($this->end > $master->end()) {
            return self::AFFECT_MODE_CUT_HEAD;
        } else {
            return self::AFFECT_MODE_HIDE;
        }
    }

    /**
     * @param Range $master
     * @return Range
     * @throws Exception
     */
    public function merge(Range $master): Range
    {
        if ($this->price !== $master->price() || $this->nextDay() < $master->start() || $this->previousDay() > $master->end()) {
            throw new Exception ('Non mergeable ranges: '. json_encode($this->toArray()) .' with '. json_encode($master->toArray()));
        }
        return new self(
            min($this->start, $master->start()),
            max($this->end, $master->end()),
            $this->price
        );
    }

    /**
     * @param Range $master
     * @return array
     * @throws Exception
     */
    public function split(Range $master)
    {
        if ($this->outOfRange($master)) {
            throw new Exception ('Ranges do not affect each other');
        } elseif ($this->start >= $master->start() || $this->end <= $master->end()) {
            throw new Exception('Unable to split the range');
        }
        return [
            new self($this->start, $master->previousDay(), $this->price),
            new self($master->nextDay(), $this->end, $this->price)
        ];
    }

    /**
     * @param Range $master
     * @return Range
     * @throws Exception
     */
    public function cutHead(Range $master): Range
    {
        if ($this->outOfRange($master)) {
            throw new Exception ('Ranges do not affect each other');
        } elseif ($this->start <= $master->start() || $this->end <= $master->end()) {
            throw new Exception('Unable to cut the range head');
        }
        return new self($master->nextDay(), $this->end, $this->price, $this->id);
    }

    /**
     * @param Range $master
     * @return Range
     * @throws Exception
     */
    public function cutTail(Range $master): Range
    {
        if ($this->outOfRange($master)) {
            throw new Exception ('Ranges do not affect each other');
        } elseif ($this->start >= $master->start() || $this->end >= $master->end()) {
            throw new Exception('Unable to cut the range tail');
        }
        return new self($this->start, $master->previousDay(), $this->price, $this->id);
    }

    /**
     * @param Range $master
     * @return bool
     */
    private function outOfRange(Range $master): bool
    {
        return ($this->end < $master->start() || $this->start > $master->end());
    }
}