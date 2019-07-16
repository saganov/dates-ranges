<?php

namespace DateRange\Core;

use Exception;

abstract class BaseRequest extends Row implements Request
{
    /**
     * @throws Exception
     */
    abstract public function validate(): void;
}