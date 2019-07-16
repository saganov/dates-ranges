<?php

namespace DateRange\Core;

interface Container
{
    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null);

    /**
     * @param $field
     * @return bool
     */
    public function has($field): bool;
}