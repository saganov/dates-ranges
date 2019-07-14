<?php

use DateRange\Core\Database;
use DateRange\Services\Range\RangeService;

class RangeServiceCest
{
    /** @var RangeService */
    private $service;
    public function _before(FunctionalTester $I)
    {
        $this->service = new RangeService(new Database('mysql://root:root@localhost/ranges'));
    }

    // tests
    public function listTest(FunctionalTester $I)
    {
        $I->assertEquals(
            [
                [
                    'start' => 'Y-m-d',
                    'end' => 'Y-m-d',
                    'price' => 15
                ],
                [
                    'start' => 'Y-m-d',
                    'end' => 'Y-m-d',
                    'price' => 25
                ]
            ],
            $this->service->list()->toArray()
        );
    }
}
