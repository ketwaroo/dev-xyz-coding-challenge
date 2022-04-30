<?php

namespace MySolution\PaydateModel;

/**
 * BasePaydateModel
 */
abstract class BasePaydateModel {

  protected $holidays;

  /**
   * Base class for pay model handlers.
   * 
   * @param array $holidays list of holidays; make sure to provide holidays here
   */
  public function __construct(array $holidays) {
    $this->holidays = $holidays;
  }

  /**
   * Tries to find the closes valid pay date based on rules.
   * 
   * @todo possible optimisation would be to find out which increment/decrement is closest to target date 
   * @param string $date 'Y-m-d'
   * @return string next valid date based on rules.
   */
  protected function findNextValidDate($date) {

    $isInitialWeekDay = $this->isDateWeekDay($date);
    // same deal for holiday, decrement
    $isInitialHoliday = $this->isHoliDay($date);
;
    // we're fine.
    if ($isInitialWeekDay && !$isInitialHoliday) {
      return $date;
    }

    // increment first, even if it's past a holiday
    if (!$isInitialWeekDay) {
      // use dark arts to reset to closest next week day
      $nextDate = $this->wriggleDate($date, '+1 weekday');
      // don't want a 2 steps forward one step back infinite loop so we keep going forward
      // this may or may not be a mistaken assumption.
      while ($this->isHoliDay($nextDate) || !$this->isDateWeekDay($nextDate)) {
        $nextDate = $this->wriggleDate($nextDate, '+1 weekday');
      }
      return $nextDate;
    }

    // similar deal but for holidays, decrement to nearest weekday that isn't a holiday.
    if ($isInitialHoliday) {
      // most likely will always be one loop unless holiday calendar changes to have continous dates.
      do {
        $nextDate = $this->wriggleDate($date, '-1 weekday');
      }
      while ($this->isHoliDay($nextDate) || !$this->isDateWeekDay($nextDate));
      return $nextDate;
    }
  }

  /**
   * Check if date falls on a weekday.
   * 
   * @param string $date 'Y-m-d' date.
   * @return bool
   */
  protected function isDateWeekDay(string $date): bool {
    return !(in_array((int)date('w', strtotime($date)), [0, 6], true));
  }

  /**
   * Check if date falls on a holiday.
   * 
   * @param string $date 'Y-m-d' date.
   * @return bool
   */
  protected function isHoliDay(string $date): bool {
    return in_array($date, $this->holidays, true);
  }

  /**
   * Increment or decrement dates up or down while staying on weekdays.
   * 
   * @param string $date Intialdate.
   * @param stirng $direction relative increment to add/subtract. '+1 weekday','-1 month', etc
   * @see https://www.php.net/manual/en/datetime.formats.relative.php
   * 
   * @return string  Adjusted date.
   */
  protected function wriggleDate(string $date, string $direction = '-1 weekday'): string {
    return $this->toISOdate("$date {$direction}", strtotime($date));
  }

  /**
   * Formats the dates to Y-m-d.
   * 
   * @param string $datetime A date/time string. Valid formats are explained in Date and Time Formats.
   * @param ?int $baseTimestamp The timestamp which is used as a base for the calculation of relative dates.
   * @return string ISO8601 date Y-m-d.
   */
  protected function toISODate(string $datetime, $baseTimestamp = null): string {
    // When pressed for time, one must yield to the higher powers of strtotime.
    return date('Y-m-d', strtotime($datetime, $baseTimestamp));
  }

}
