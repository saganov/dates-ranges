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
        $I->sendPUT('/ranges/1/5/15');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,5,15]);
        // Second Step
        $I->sendPUT('/ranges/20/25/15');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,5,15], [20,25,15]);
        // Third Step
        $I->sendPUT('/ranges/4/21/45');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,3,15], [4,21,45], [22,25,15]);
        // Fourth Step
        $I->sendPUT('/ranges/3/21/15');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,25,15]);
    }
}
