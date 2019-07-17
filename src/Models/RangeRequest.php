<?php

namespace DateRange\Models;

use DateRange\Core\BaseRequest;
use DateRange\Core\InvalidRequestException;
use DateRange\Services\Range\Range;
use DateTime;

class RangeRequest extends BaseRequest implements Range
{
    private const START = 'start';
    private const END   = 'end';
    private const PRICE = 'price';

    /**
     * @throws InvalidRequestException
     */
    public function validate(): void
    {
        $errors = [];
        foreach ([self::START, self::END, self::PRICE] as $field) {
            if (!$this->has($field)) {
                $errors[] = sprintf('Missed mandatory field: %s', $field);
            } elseif (empty($this->get($field))) {
                $errors[] = sprintf('Field %s must have value', $field);
            }
        }
        foreach ([self::START, self::END] as $field) {
            if ((DateTime::createFromFormat('Y-m-d', $this->get($field)) === false)) {
                $errors[] = sprintf('Field %s have to be date in Y-m-d format', $field);
            }
        }
        if (!is_numeric($this->get(self::PRICE))) {
            $errors[] = sprintf('Field %s have to be float', self::PRICE);
        }
        if ($this->get(self::START) > $this->get(self::END)) {
            $errors[] = 'Start have to be be before end';
        }
        if (count($errors)) {
            throw new InvalidRequestException('Invalid request. '. implode ('. ', $errors));
        }
    }

    /**
     * @return string Start date (Y-m-d)
     */
    public function start(): string
    {
        return $this->get(self::START);
    }

    /**
     * @return string End date (Y-m-d)
     */
    public function end(): string
    {
        return $this->get(self::END);
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return (float)$this->get(self::PRICE);
    }
}
