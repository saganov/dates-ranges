<?php

namespace DateRange\Core;

use Exception;

interface Request
{
    /**
     * @throws Exception
     */
    public function validate(): void;
}