<?php

use Codeception\Util\HttpCode;

class RangesBaseCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function listTest(ApiTester $I)
    {
        $I->sendGET('/api/v1/ranges/');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function createTest(ApiTester $I)
    {
        $I->sendPUT('/api/v1/ranges/');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function deleteTest(ApiTester $I)
    {
        $I->sendDELETE('/api/v1/ranges/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
