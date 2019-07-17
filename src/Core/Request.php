<?php

namespace DateRange\Core;

interface Request
{
    /**
     * @throws InvalidRequestException
     */
    public function validate(): void;
}