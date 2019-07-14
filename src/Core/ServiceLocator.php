<?php

namespace DateRange\Core;

use Exception;

abstract class ServiceLocator
{
    protected $declarations = [];
    protected $services = [];

    abstract public function register(): void;

    /**
     * @param string $abstraction
     * @param callable $implementation
     */
    public function set(string $abstraction, Callable $implementation)
    {
        $this->declarations[$abstraction] = $implementation;
    }

    /**
     * @param string $abstraction
     * @return mixed
     * @throws Exception
     */
    public function get(string $abstraction)
    {
        if (!count($this->declarations)) {
            $this->register();
        }
        if (!key_exists($abstraction, $this->declarations)) {
            throw new Exception('Unknown service: '. $abstraction);
        }
        if (!key_exists($abstraction, $this->services)) {
            $this->services[$abstraction] = call_user_func($this->declarations[$abstraction]);
        }
        return $this->services[$abstraction];
    }
}
