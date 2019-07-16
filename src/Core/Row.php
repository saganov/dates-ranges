<?php

namespace DateRange\Core;

class Row implements Container
{
    /** @var array  */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return ($this->has($key) ? $this->data[$key] : $default);
    }

    /**
     * @param $field
     * @return bool
     */
    public function has($field): bool
    {
        return (key_exists($field, $this->data));
    }
}