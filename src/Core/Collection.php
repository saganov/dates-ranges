<?php

namespace DateRange\Core;

use Exception;

class Collection implements ArraySerializable
{
    /**
     * @var ArraySerializable[]
     */
    private $collection = [];

    /**
     * Collection constructor.
     * @param array $collection
     */
    public function __construct(array $collection = [])
    {
        $this->collection = $collection;
    }

    /**
     * @param ArraySerializable $object
     * @return $this
     */
    public function push(ArraySerializable $object)
    {
        $this->collection[] = $object;
        return $this;
    }

    /**
     * @param Callable $callable
     * @return $this
     * @throws Exception
     */
    public function walk($callable)
    {
        if (!is_callable($callable)) {
            throw new Exception('You should pass callable function to walk it trough collection');
        }
        foreach ($this->collection as $key => $object) {
            call_user_func($callable, $object, $key);
        }
        return $this;
    }

    /**
     * @param $callable
     * @return Collection
     * @throws Exception
     */
    public function map($callable)
    {
        if (!is_callable($callable)) {
            throw new Exception('You should pass callable function to walk it trough collection');
        }
        return new self(array_map($callable, $this->collection));
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $objectArray = [];
        foreach ($this->collection as $object) {
            $objectArray[] = $object->toArray();
        }
        return $objectArray;
    }

    /**
     * @param string $getter
     * @return array
     */
    public function column(string $getter): array
    {
        $column = [];
        foreach ($this->collection as $object) {
            $column[] = call_user_func([$object, $getter]);
        }
        return $column;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }
}