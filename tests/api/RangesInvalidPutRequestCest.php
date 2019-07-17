<?php

use Codeception\Util\HttpCode;

class RangesInvalidPutRequestCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function emptyPayloadTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode([]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //TODO: add `flow/jsonpath` to require-dev of composer.json
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }

    public function emptyValuesTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '', 'end' => '', 'price' => '']));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }

    // TODO: use date provider to test all combinations of missed fields
    // TODO: all combinations could include all fields missed - emptyPayloadTest
    public function missedFieldTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => '2019-07-21']));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }

    // TODO: use date provider to test all combinations of missed fields
    // TODO: all combinations could include all fields missed - emptyValuesTest
    public function missedValueTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => '2019-07-21', 'price' => '']));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }

    // TODO: use date provider to test several invalid date formats
    public function invalidStartFormatTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '21-07-2019', 'end' => '2019-07-21', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }

    // TODO: use same date provider to test end date as well
    public function invalidEndFormatTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => '21-07-2019', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }

    // TODO: use date provider to test several invalid price formats including negative numbers
    public function invalidPriceFormatTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => '2019-07-21', 'price' => '15.00d']));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }

    public function invalidRangeTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => '2019-07-09', 'price' => 15.00]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        //$I->seeResponseJsonMatchesJsonPath('$.message');
    }
}
