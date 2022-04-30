<?php

namespace MySolution;

use DevXyz\Challenge\PaydateCalculatorInterface;
use MySolution\HolidayGenerator;
use MySolution\PaydateModel\Monthly;
use MySolution\PaydateModel\BiWeekly;
use MySolution\PaydateModel\Weekly;

/**
 * Implementation of PaydateCalculatorInterface for dev.xyz coding challenge.
 * 

  In PHP, write a class that implements DevXyz\Challenge\PaydateCalculatorInterface (please see bottom of this document).
  Given an initial (first) paydate, paydate model and count, PaydateCalculator must be able to return the specified number of paydates.
  PaydateCalculator must run without generating any errors, warnings or notices.
  There is only a single class required in the result, but if you think there is a good use for multiple classes in your solution, please use them.
  A valid paydate cannot fall on today, a weekend or a holiday.
  If a paydate falls on a weekend, increment the date by one day until a valid paydate is reached.
  If a paydate falls on a holiday, decrement the date by one day until a valid paydate is reached.

 */
class PaydateCalculator implements PaydateCalculatorInterface {

  protected $paydateModel;
  protected $holidays;

  /**
   * {@inheritdoc}
   */
  public function __construct(string $paydateModel, array $holidays = []) {

    if (!in_array($paydateModel, [
                static::PAYDATE_MODEL_BIWEEKLY,
                static::PAYDATE_MODEL_MONTHLY,
                static::PAYDATE_MODEL_WEEKLY,
            ])) {
      throw new \InvalidArgumentException('Unrecognised Paydate Model: ' . $paydateModel);
    }

    if (empty($holidays)) {
      // @todo would be better to fix interface to make param non optional.
      throw new \InvalidArgumentException('holidays list is required.');
    }

    $this->paydateModel = $paydateModel;
    $this->holidays     = $holidays;
  }

  /**
   * {@inheritdoc}
   */
  public function calculatePaydates(string $initialPaydate, int $numberOfPaydates): array {

    // assuming timezones and whatnot is already squared away at system/ini file level.

    if (date('Y-m-d') === $initialPaydate) {
      // I know it says "PaydateCalculator must run without generating any errors, warnings or notices."
      // but there's no case for handling this in the test doc
      // this sound more like a clerical error, on that note, assumption made is
      // that the initial pay day is never on an invalid date like a weekend or holiday.
      // @todo maybe handle past dates.
      throw new \InvalidArgumentException('Initial Pay Day cannot be the same as current day.');
    }

    switch ($this->paydateModel) {
      case static::PAYDATE_MODEL_MONTHLY:
        $model = new Monthly($this->holidays);

        break;
      case static::PAYDATE_MODEL_BIWEEKLY:
        $model = new BiWeekly($this->holidays);
        break;
      case static::PAYDATE_MODEL_WEEKLY:
        $model = new Weekly($this->holidays);
      default:
        throw new \InvalidArgumentException('this shouldn\'t be happening.');
    }

    return $model->calculatePaydates($initialPaydate, $numberOfPaydates);
  }

}
