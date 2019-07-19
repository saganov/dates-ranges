<?php

use Codeception\Example;
use DateRange\Models\RangeRequest;
use DateRange\Services\Range\DbRange;

class DbRangeCest
{
    public function _before(UnitTester $I)
    {
    }

    /**
     * @example { "start": "2019-07-01", "end": "2019-07-08", "price": 15 }
     * @example { "start": "2019-07-23", "end": "2019-07-25", "price": 15 }
     * @example { "start": "2019-07-01", "end": "2019-07-09", "price": 16 }
     * @example { "start": "2019-07-22", "end": "2019-07-25", "price": 16 }
     */
    public function affectModeNoneTest(UnitTester $I, Example $example)
    {
        // TODO: Replace RangeRequest with the mock
        $dbRange = new DbRange('2019-07-10', '2019-07-21', 15);
        $I->assertEquals(
            DbRange::AFFECT_MODE_NONE,
            $dbRange->affectMode(
                new RangeRequest([
                    'start' => $example['start'],
                    'end'   => $example['end'],
                    'price' => $example['price']
                ])
            )
        );
    }

    /**
     * @example { "start": "2019-07-01", "end": "2019-07-09", "price": 15 }
     * @example { "start": "2019-07-01", "end": "2019-07-10", "price": 15 }
     * @example { "start": "2019-07-01", "end": "2019-07-11", "price": 15 }
     * @example { "start": "2019-07-20", "end": "2019-07-25", "price": 15 }
     * @example { "start": "2019-07-21", "end": "2019-07-25", "price": 15 }
     * @example { "start": "2019-07-22", "end": "2019-07-25", "price": 15 }
     */
    public function affectModeMergeTest(UnitTester $I, Example $example)
    {
        // TODO: Replace RangeRequest with the mock
        $dbRange = new DbRange('2019-07-10', '2019-07-21', 15);
        $I->assertEquals(
            DbRange::AFFECT_MODE_MERGE,
            $dbRange->affectMode(
                new RangeRequest([
                    'start' => $example['start'],
                    'end'   => $example['end'],
                    'price' => $example['price']
                ])
            )
        );
    }

    /**
     * @example { "start": "2019-07-11", "end": "2019-07-20", "price": 16 }
     * @example { "start": "2019-07-11", "end": "2019-07-12", "price": 16 }
     * @example { "start": "2019-07-19", "end": "2019-07-20", "price": 16 }
     * @example { "start": "2019-07-15", "end": "2019-07-16", "price": 16 }
     */
    public function affectModeSplitTest(UnitTester $I, Example $example)
    {
        // TODO: Replace RangeRequest with the mock
        $dbRange = new DbRange('2019-07-10', '2019-07-21', 15);
        $I->assertEquals(
            DbRange::AFFECT_MODE_SPLIT,
            $dbRange->affectMode(
                new RangeRequest([
                    'start' => $example['start'],
                    'end'   => $example['end'],
                    'price' => $example['price']
                ])
            )
        );
    }

    /**
     * @example { "start": "2019-07-11", "end": "2019-07-21", "price": 16 }
     * @example { "start": "2019-07-11", "end": "2019-07-25", "price": 16 }
     * @example { "start": "2019-07-20", "end": "2019-07-21", "price": 16 }
     * @example { "start": "2019-07-20", "end": "2019-07-22", "price": 16 }
     * @example { "start": "2019-07-21", "end": "2019-07-22", "price": 16 }
     * @example { "start": "2019-07-21", "end": "2019-07-23", "price": 16 }
     */
    public function affectModeCutTailTest(UnitTester $I, Example $example)
    {
        // TODO: Replace RangeRequest with the mock
        $dbRange = new DbRange('2019-07-10', '2019-07-21', 15);
        $I->assertEquals(
            DbRange::AFFECT_MODE_CUT_TAIL,
            $dbRange->affectMode(
                new RangeRequest([
                    'start' => $example['start'],
                    'end'   => $example['end'],
                    'price' => $example['price']
                ])
            )
        );
    }

    /**
     * @example { "start": "2019-07-08", "end": "2019-07-20", "price": 16 }
     * @example { "start": "2019-07-09", "end": "2019-07-10", "price": 16 }
     * @example { "start": "2019-07-10", "end": "2019-07-11", "price": 16 }
     */
    public function affectModeCutHeadTest(UnitTester $I, Example $example)
    {
        // TODO: Replace RangeRequest with the mock
        $dbRange = new DbRange('2019-07-10', '2019-07-21', 15);
        $I->assertEquals(
            DbRange::AFFECT_MODE_CUT_HEAD,
            $dbRange->affectMode(
                new RangeRequest([
                    'start' => $example['start'],
                    'end'   => $example['end'],
                    'price' => $example['price']
                ])
            )
        );
    }

    /**
     * @example { "start": "2019-07-10", "end": "2019-07-21", "price": 16 }
     * @example { "start": "2019-07-10", "end": "2019-07-22", "price": 16 }
     * @example { "start": "2019-07-10", "end": "2019-07-23", "price": 16 }
     * @example { "start": "2019-07-09", "end": "2019-07-23", "price": 16 }
     * @example { "start": "2019-07-09", "end": "2019-07-22", "price": 16 }
     * @example { "start": "2019-07-09", "end": "2019-07-21", "price": 16 }
     * @example { "start": "2019-07-08", "end": "2019-07-23", "price": 16 }
     */
    public function affectModeHideTest(UnitTester $I, Example $example)
    {
        // TODO: Replace RangeRequest with the mock
        $dbRange = new DbRange('2019-07-10', '2019-07-21', 15);
        $I->assertEquals(
            DbRange::AFFECT_MODE_HIDE,
            $dbRange->affectMode(
                new RangeRequest([
                    'start' => $example['start'],
                    'end'   => $example['end'],
                    'price' => $example['price']
                ])
            )
        );
    }
}
