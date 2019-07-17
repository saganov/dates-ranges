<?php

use Codeception\Util\HttpCode;
use Codeception\Example;

class RangesInvalidPutRequestCest
{
    public function _before(ApiTester $I)
    {
    }

    public function emptyPayloadTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode([]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }

    public function emptyValuesTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '', 'end' => '', 'price' => '']));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }

    /**
     * @example {                                             "price": 15 }
     * @example {                        "end": "2019-07-21"              }
     * @example {                        "end": "2019-07-21", "price": 15 }
     * @example { "start": "2019-07-10"                                   }
     * @example { "start": "2019-07-10",                      "price": 15 }
     * @example { "start": "2019-07-10", "end": "2019-07-21"              }
     */
    public function missedFieldTest(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode($example));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }

    /**
     * @example { "start": "",           "end": "",           "price": 15 }
     * @example { "start": "",           "end": "2019-07-21", "price": "" }
     * @example { "start": "",           "end": "2019-07-21", "price": 15 }
     * @example { "start": "2019-07-10", "end": "",           "price": "" }
     * @example { "start": "2019-07-10", "end": "",           "price": 15 }
     * @example { "start": "2019-07-10", "end": "2019-07-21", "price": "" }
     */
    public function missedValueTest(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode($example));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }

    /**
     * @example { "date": "10-07-2019" }
     * @example { "date": "2019.07.10" }
     * @example { "date": "2019/07/10" }
     * @example { "date": "2019-07-10 22:22:22" }
     * @example { "date": "20190710" }
     * @example { "date": "delete" }
     */
    public function invalidStartFormatTest(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => $example['date'], 'end' => '2019-07-21', 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }

    /**
     * @example { "date": "10-07-2019" }
     * @example { "date": "2019.07.10" }
     * @example { "date": "2019/07/10" }
     * @example { "date": "2019-07-10 22:22:22" }
     * @example { "date": "20190710" }
     * @example { "date": "delete" }
     */
    public function invalidEndFormatTest(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => $example['date'], 'price' => 15]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }

    /**
     * @example { "price": "2019-07-10" }
     * @example { "price": "15.00d" }
     * @example { "price": "15/10" }
     * @example { "price": "delete" }
     * TODO: How about negative price -15?
     */
    public function invalidPriceFormatTest(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => '2019-07-21', 'price' => $example['price']]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }

    public function invalidRangeTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json;charset=UTF-8');
        $I->sendPUT('/ranges/', json_encode(['start' => '2019-07-10', 'end' => '2019-07-09', 'price' => 15.00]));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.message');
    }
}
