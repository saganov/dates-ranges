<?php

use Codeception\Util\HttpCode;

class ExampleSecondCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function sequenceTest(AcceptanceTester $I)
    {
        // First Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-01', 'end' => '2019-07-05', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([
                ['start' => '2019-07-01', 'end' => '2019-07-05', 'price' => 15],
            ]),
            $I->grabResponse()
        );
        // Second Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-20', 'end' => '2019-07-25', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([
                ['start' => '2019-07-01', 'end' => '2019-07-05', 'price' => 15],
                ['start' => '2019-07-20', 'end' => '2019-07-25', 'price' => 15],
            ]),
            $I->grabResponse()
        );
        // Third Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-04', 'end' => '2019-07-21', 'price' => 45]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([
                ['start' => '2019-07-01', 'end' => '2019-07-03', 'price' => 15],
                ['start' => '2019-07-04', 'end' => '2019-07-21', 'price' => 45],
                ['start' => '2019-07-22', 'end' => '2019-07-25', 'price' => 15],
            ]),
            $I->grabResponse()
        );
        // Fourth Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-03', 'end' => '2019-07-21', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([
                ['start' => '2019-07-01', 'end' => '2019-07-25', 'price' => 15],
            ]),
            $I->grabResponse()
        );
    }
}
