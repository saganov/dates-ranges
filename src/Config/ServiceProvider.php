<?php

namespace DateRange\Config;

use DateRange\Core\Database;
use DateRange\Core\ServiceLocator;
use DateRange\Services\Range\RangeService;

class ServiceProvider extends ServiceLocator
{
    public function register(): void
    {
        $this->set(Database::class, function () {
            return new Database('mysql://root:root@localhost/ranges');
        });
        $this->set(RangeService::class, function () {
            return new RangeService($this->get(Database::class));
        });
    }
}
