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
        $I->sendGET('/ranges/');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function createTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-06-01', 'end' => '2019-06-21', 'price' => 15.00]));
        $I->seeResponseCodeIs(HttpCode::CREATED);
    }

    public function deleteTest(ApiTester $I)
    {
        $I->sendDELETE('/ranges/1');
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }
}
