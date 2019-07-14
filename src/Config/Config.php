<?php

namespace DateRange\Config;

class Config
{
    public function get($key, $default)
    {
        return getenv($key) ?: $default;
    }

    public function dbUrl()
    {
        return sprintf(
            'mysql://%s:%s@%s/%s',
            $this->get('DB_USER', 'root'),
            $this->get('DB_PASS', 'root'),
            $this->get('DB_HOST', '127.0.0.1'),
            $this->get('DB_NAME', 'ranges')
        );
    }
}