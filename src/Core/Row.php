<?php

namespace DateRange\Core;

class Row
{
    /** @var array  */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get($key, $default = null)
    {
        return (key_exists($key, $this->data) ? $this->data[$key] : $default);
    }
}