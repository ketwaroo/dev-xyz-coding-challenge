<?php

namespace MySolution\PaydateModel;

/**
 * Weekly paymodel handler.
 */
class Weekly extends BasePaydateModel implements PaydateModelInterface {
  /**
   * {@inheritDoc}
   */
  public function calculatePaydates(string $initialPaydate, int $numberOfPaydates): array {
    $intervals = [];

    for ($i = 0; $i < $numberOfPaydates; $i++) {
      // well, it works.
      $intervals[] = $this->findNextValidDate($this->wriggleDate($initialPaydate, '+' . ($i * 5) . ' weekday'));
    }
    return $intervals;
  }
}
