<?php

namespace DateRange\Core;

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
}