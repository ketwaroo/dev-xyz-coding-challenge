<?php

namespace MySolution;

use DevXyz\Challenge\PaydateCalculatorInterface;
use MySolution\HolidayGenerator;
use MySolution\UserInputValidator;
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

  /**
   * current paydate model.
   * @var string
   */
  protected $paydateModel;

  /**
   * List of holidays.
   * @var array
   */
  protected $holidays;

  /**
   * Validator helper.
   * 
   * @var UserInputValidator
   */
  protected $validator;

  /**
   * {@inheritDoc}
   */
  public function __construct(string $paydateModel, array $holidays = []) {

    $this->validator = $this->newUserInputValidator();

    $tests = array_filter([
        $this->validator->validatePaydateModel($paydateModel),
        $this->validator->validateHolidays($holidays),
    ]);

    if (!empty($tests)) {
      throw new \InvalidArgumentException("Errors in input:\n" . implode("\n", $tests));
    }

    $this->paydateModel = trim($paydateModel);
    $this->holidays     = $holidays;
  }

  /**
   * {@inheritDoc}
   */
  public function calculatePaydates(string $initialPaydate, int $numberOfPaydates): array {
    $validator = $this->getValidator();
    $tests = array_filter([
        // this sound more like a clerical error, on that note, assumption made is
        // that the initial pay day is never on an invalid date like a weekend or holiday.
        date('Y-m-d') === $initialPaydate ? 'Initial Pay Day cannot be the same as current day.' : null,
        $validator->validateDate($initialPaydate),
        $validator->validatePositiveInt($numberOfPaydates, 'Number of paydates'),
    ]);

    if (!empty($tests)) {
      throw new \InvalidArgumentException("Errors in input:\n" . implode("\n", $tests));
    }

    $model = $this->newPaydateModelService($this->getPaydateModel());

    return $model->calculatePaydates($initialPaydate, $numberOfPaydates);
  }

  /**
   * New instance of validator.
   * @codeCoverageIgnore 
   * @return UserInputValidator
   */
  protected function newUserInputValidator() {
    return new UserInputValidator();
  }

  /**
   * Creates new pay model handler.
   * 
   * Excluded from test or now due to `new` keyword usage.
   * This is basically a factory method.
   * 
   * @codeCoverageIgnore 
   * 
   * @param string $schema type of pay model
   * 
   * @see DevXyz\Challenge\PaydateCalculatorInterface::PAYDATE_MODEL_* constants.
   * 
   * @return PaydateModel\PaydateModelInterface
   * 
   * @throws \RuntimeException On unexpected error.
   */
  protected function newPaydateModelService(string $schema) {

    switch ($schema) {

      case static::PAYDATE_MODEL_MONTHLY:

        $model = new Monthly($this->getHolidays());
        break;

      case static::PAYDATE_MODEL_BIWEEKLY:
        $model = new BiWeekly($this->getHolidays());
        break;

      case static::PAYDATE_MODEL_WEEKLY:
        $model = new Weekly($this->getHolidays());
        break;

      default:
        // kept in case validator is broken.
        throw new \RuntimeException('this shouldn\'t be happening.');
    }

    return $model;
  }

  /**
   * 
   * @codeCoverageIngore
   * @return string
   */
  public function getPaydateModel(): string {
    return $this->paydateModel;
  }

  /**
   * 
   * @codeCoverageIgnore
   * @return array
   */
  public function getHolidays(): array {
    return $this->holidays;
  }

  /**
   * 
   * @codeCoverageIgnore
   * @return UserInputValidator
   */
  protected function getValidator() {
    return $this->validator;
  }

}
