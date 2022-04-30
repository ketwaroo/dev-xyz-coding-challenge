<?php

namespace MySolution;

/**
 * HolidayGenerator
 */
class HolidayGenerator {

  /**
   * Cache of generated holidays.
   * 
   * @var array
   */
  protected $holidays;

  const HOLIDAY_DEF = [
      'January 01', // new year
      'third Monday of January', // MLK day
      'third Monday of February', // presidents day?
      'last Monday of May', // memorial day
      'July 04',
      'first Monday of September', // labor day
      'second Monday of October', // soon to be defunct Columbus day
      'November 11', //Remembrance Day
      'last Thursday of November', // thanksgiving
      'December 25', // christmas
  ];

  /**
   * Generates holiday list for a specified year.
   * 
   * @param int $year
   * @return array List of holidays for that year.
   */
  public function generateHolidays(int $year): array {

    if (!isset($this->holidays[$year])) {

      $init = static::HOLIDAY_DEF;

      array_walk(
              $init,
              function (string &$date, $yey, $baseTime) {
                $date = date('Y-m-d', strtotime($date, $baseTime));
              },
              strtotime("{$year}-01-01")
      );

      $this->holidays[$year] = $init;
    }
    
    return $this->holidays[$year];
  }

}
