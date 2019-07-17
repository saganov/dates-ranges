<?php

namespace DateRange\Core;

abstract class BaseRequest extends Row implements Request
{
    /**
     * @throws InvalidRequestException
     */
    abstract public function validate(): void;
}