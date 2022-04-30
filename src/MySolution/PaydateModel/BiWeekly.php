<?php

namespace MySolution\PaydateModel;


/**
 * BiWeekly paymodel hanlder.
 */
class BiWeekly extends BasePaydateModel implements PaydateModelInterface{
  
  /**
   * {@inheritDoc}
   */
  public function calculatePaydates(string $initialPaydate, int $numberOfPaydates): array {
    $intervals = [];

    for ($i = 0; $i < $numberOfPaydates; $i++) {
      // well, it works.
      $intervals[] = $this->findNextValidDate($this->wriggleDate($initialPaydate, '+' . ($i * 10) . ' weekday'));
    }
    return $intervals;
  }

}
