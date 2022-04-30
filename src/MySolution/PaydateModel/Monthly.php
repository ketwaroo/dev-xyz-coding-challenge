<?php
namespace MySolution\PaydateModel;

/**
 * Monthly pay model handler.
 */
class Monthly extends BasePaydateModel implements PaydateModelInterface{
  
  /**
   * {@inheritDoc}
   */
  public function calculatePaydates(string $initialPaydate, int $numberOfPaydates): array {
    $intervals = [];

    for ($i = 0; $i < $numberOfPaydates; $i++) {

      $intervals[] = $this->findNextValidDate($this->wriggleDate($initialPaydate, "+{$i} month"));
    }
    return $intervals;
  }
}
