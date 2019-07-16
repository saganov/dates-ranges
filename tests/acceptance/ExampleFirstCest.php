<?php

use Codeception\Util\HttpCode;

class ExampleFirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function sequenceTest(AcceptanceTester $I)
    {
        // First Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-01', 'end' => '2019-07-10', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([['start' => '2019-07-01', 'end' => '2019-07-10', 'price' => 15]]),
            $I->grabResponse()
        );
        // Second Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-05', 'end' => '2019-07-20', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([['start' => '2019-07-01', 'end' => '2019-07-20', 'price' => 15]]),
            $I->grabResponse()
        );
        // Third Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-02', 'end' => '2019-07-08', 'price' => 45]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([
                ['start' => '2019-07-01', 'end' => '2019-07-01', 'price' => 15],
                ['start' => '2019-07-02', 'end' => '2019-07-08', 'price' => 45],
                ['start' => '2019-07-09', 'end' => '2019-07-20', 'price' => 15],
            ]),
            $I->grabResponse()
        );
        // Fourth Step
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-09', 'end' => '2019-07-10', 'price' => 45]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->sendGET('/ranges/');
        $I->assertEquals(
            json_encode([
                ['start' => '2019-07-01', 'end' => '2019-07-01', 'price' => 15],
                ['start' => '2019-07-02', 'end' => '2019-07-10', 'price' => 45],
                ['start' => '2019-07-11', 'end' => '2019-07-20', 'price' => 15],
            ]),
            $I->grabResponse()
        );
    }
}
