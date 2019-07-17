<?php

use Codeception\Util\HttpCode;

class NonExistentResourceCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        $I->sendGET('/wrong-resource/');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }
}
