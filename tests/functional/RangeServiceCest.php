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
        $I->haveInDatabase('ranges', [
            'start' => '2019-07-10',
            'end' => '2019-07-21',
            'price' => 15
        ]);
        $I->haveInDatabase('ranges', [
            'start' => '2019-07-01',
            'end' => '2019-07-05',
            'price' => 25
        ]);
        $I->assertEquals(
            [
                [
                    'start' => '2019-07-01',
                    'end' => '2019-07-05',
                    'price' => 25
                ],
                [
                    'start' => '2019-07-10',
                    'end' => '2019-07-21',
                    'price' => 15
                ],
            ],
            $this->service->list()->toArray()
        );
    }

    public function saveTest(FunctionalTester $I)
    {
        $range = [
            'start' => '2019-07-10',
            'end' => '2019-07-21',
            'price' => 15
        ];
        $this->service->save($range);
        $I->seeInDatabase('ranges', $range);
    }
}
