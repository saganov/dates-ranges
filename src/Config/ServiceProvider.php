<?php

namespace DateRange\Config;

use DateRange\Core\Database;
use DateRange\Core\ServiceLocator;
use DateRange\Services\Range\RangeService;

class ServiceProvider extends ServiceLocator
{
    /** @var Config */
    private $config;

    /**
     * ServiceProvider constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function register(): void
    {
        $this->set(Database::class, function () {
            return new Database($this->config->dbUrl());
        });
        $this->set(RangeService::class, function () {
            return new RangeService($this->get(Database::class));
        });
    }
}
