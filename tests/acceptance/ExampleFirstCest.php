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
        $I->sendPUT('/ranges/1/10/15');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,10,15]);
        // Second Step
        $I->sendPUT('/ranges/5/20/15');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,20,15]);
        // Third Step
        $I->sendPUT('/ranges/2/8/45');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,1,15], [2,8,45], [9,20,15]);
        // Fourth Step
        $I->sendPUT('/ranges/9/10/45');
        $I->seeResponseCodeIs(HttpCode::OK);
        // TODO: seeRecordsInDb([1,1,15], [2,10,45], [11,20,15]);
    }
}
