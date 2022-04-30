<?php

namespace Test\functional;

use PHPUnit\Framework\TestCase;
use DevXyz\Challenge\PaydateCalculatorInterface;
use MySolution\PaydateCalculator;

/**
 * PayDateCalculatorTest
 * 
 * somewhat simple functional test.
 */
class PayDateCalculatorTest extends TestCase {

  public function dataCalculatePaydates() {

    $holidays = [
        '2014-01-01',
        '2014-01-20',
        '2014-02-17',
        '2014-05-26',
        '2014-07-04',
        '2014-09-01',
        '2014-10-13',
        '2014-11-11',
        '2014-11-27',
        '2014-12-25',
    ];

    return [
        [
            PaydateCalculatorInterface::PAYDATE_MODEL_MONTHLY,
            '2014-01-02',
            10,
            $holidays,
            [
                '2014-01-02',
                '2014-02-03',
                '2014-03-03',
                '2014-04-02',
                '2014-05-02',
                '2014-06-02',
                '2014-07-02',
                '2014-08-04',
                '2014-09-02',
                '2014-10-02',
            ],
        ],
        [
            PaydateCalculatorInterface::PAYDATE_MODEL_BIWEEKLY,
            '2014-01-06',
            8,
            $holidays,
            [
                '2014-01-06',
                '2014-01-17',
                '2014-02-03',
                '2014-02-14',
                '2014-03-03',
                '2014-03-17',
                '2014-03-31',
                '2014-04-14',
            ],
        ],
        [
            PaydateCalculatorInterface::PAYDATE_MODEL_WEEKLY,
            '2014-01-06',
            26,
            $holidays,
            [
                '2014-01-06',
                '2014-01-13',
                '2014-01-17',
                '2014-01-27',
                '2014-02-03',
                '2014-02-10',
                '2014-02-14',
                '2014-02-24',
                '2014-03-03',
                '2014-03-10',
                '2014-03-17',
                '2014-03-24',
                '2014-03-31',
                '2014-04-07',
                '2014-04-14',
                '2014-04-21',
                '2014-04-28',
                '2014-05-05',
                '2014-05-12',
                '2014-05-19',
                '2014-05-23',
                '2014-06-02',
                '2014-06-09',
                '2014-06-16',
                '2014-06-23',
                '2014-06-30',
            ],
        ],
    ];
  }

  /**
   * Tests schemaalculatePaydates().
   * 
   * @dataProvider dataCalculatePaydates
   * 
   * @param string $paydateModel
   * @param string $initialDate
   * @param int $numberofPaydates
   * @param array $holidays
   * @param array $expected
   */
  public function testCalculatePaydates(string $paydateModel, string $initialDate, int $numberofPaydates, array $holidays, array $expected) {


    $subject = new PaydateCalculator($paydateModel, $holidays);

    $actual = $subject->calculatePaydates($initialDate, $numberofPaydates);

    $this->assertSame($expected, $actual);
  }

}
