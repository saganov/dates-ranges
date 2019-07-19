<?php

use DateRange\Core\Database;
use DateRange\Models\RangeRequest;
use DateRange\Services\Range\RangeService;

class RangeServiceCest
{
    /** @var RangeService */
    private $service;
    public function _before(FunctionalTester $I)
    {
        $this->service = new RangeService(new Database('mysql://root:root@127.0.0.1/ranges'));
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
        $this->service->save(new RangeRequest($range));
        $I->seeInDatabase('ranges', $range);
    }

    public function deleteTest(FunctionalTester $I)
    {
        $first = [
            'start' => '2019-07-10',
            'end' => '2019-07-15',
            'price' => 15
        ];
        $second = [
            'start' => '2019-07-18',
            'end' => '2019-07-21',
            'price' => 45
        ];
        $third = [
            'start' => '2019-08-01',
            'end' => '2019-08-05',
            'price' => 25
        ];
        $I->haveInDatabase('ranges', $first);
        $I->haveInDatabase('ranges', $second);
        $I->haveInDatabase('ranges', $third);
        $this->service->delete(new RangeRequest($second));
        $I->seeInDatabase('ranges', $first);
        $I->dontSeeInDatabase('ranges', $second);
        $I->seeInDatabase('ranges', $third);
    }

    public function deleteAllTest(FunctionalTester $I)
    {
        $first = [
            'start' => '2019-07-10',
            'end' => '2019-07-15',
            'price' => 15
        ];
        $second = [
            'start' => '2019-07-18',
            'end' => '2019-07-21',
            'price' => 45
        ];
        $third = [
            'start' => '2019-08-01',
            'end' => '2019-08-05',
            'price' => 25
        ];
        $I->haveInDatabase('ranges', $first);
        $I->haveInDatabase('ranges', $second);
        $I->haveInDatabase('ranges', $third);
        $this->service->delete();
        $I->dontSeeInDatabase('ranges', $first);
        $I->dontSeeInDatabase('ranges', $second);
        $I->dontSeeInDatabase('ranges', $third);
    }
}
