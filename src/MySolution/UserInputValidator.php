<?php

namespace MySolution;

use DevXyz\Challenge\PaydateCalculatorInterface;

/**
 * Some validation utils.
 * 
 * All validator methods return null if no errors found and error message string
 * if there are issues. Seems a bit backwards, as boolean true is usually expected
 * but it makes filtering a bit easier.
 * 
 */
class UserInputValidator {

  /**
   * Tests paydate model.
   * 
   * @param string $paydateModel Pay model.
   * @return string|null Returns an error message if invalid.
   */
  public function validatePaydateModel(string $paydateModel) {
    if (!in_array($paydateModel, [
                PaydateCalculatorInterface::PAYDATE_MODEL_BIWEEKLY,
                PaydateCalculatorInterface::PAYDATE_MODEL_MONTHLY,
                PaydateCalculatorInterface::PAYDATE_MODEL_WEEKLY,
            ])) {
      return "Unrecognised Paydate Model: `{$paydateModel}`. Check allowed values.";
    }
  }

  /**
   * Test date.
   * 
   * @param string $dateString input string
   * @return string|null Returns an error message if invalid.
   */
  public function validateDate(string $dateString) {
    $test = date_parse_from_format('Y-m-d', $dateString);
    if (0 !== $test['error_count'] || 0 !== $test['warning_count']) {
      return "Date should be a valid date in yyyy-mm-dd format. e.g. " . date('Y-m-d') . ". You entered `{$dateString}`.";
    }
  }

  /**
   * Test holiday list.
   * 
   * @todo this is barebones. doesn't actually validate dates.
   *       Assumption that there should always be some holidays is based off
   *       specs from paydate calcualtor interface
   * @param array $holidays list of holdays
   * @return string|null Returns an error message if invalid.
   */
  public function validateHolidays(array $holidays) {
    if (empty($holidays)) {
      return 'holidays list is required.';
    }
  }

  /**
   * Test an integer if positive integer.
   * 
   * @param mixed $input input
   * @param int $inputLabel input label
   * @return string|null Returns an error message if invalid.
   */
  public function validatePositiveInt($input, string $inputLabel) {

    // the bare minimum
    $intval = (int) $input;
    if ($intval < 1) {
      return "{$inputLabel} should be parsable an integer greater than zero. You entered `{$input}`.";
    }
  }

}
