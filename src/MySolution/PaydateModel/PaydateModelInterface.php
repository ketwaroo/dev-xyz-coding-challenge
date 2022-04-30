<?php

namespace MySolution\PaydateModel;

/**
 * Interface for pay date models.
 */
interface PaydateModelInterface {
  
    /**
     * takes first paydate and generates the next $numberOfPaydates paydates.
     *
     * @param string $initialPaydate First paydate as a string in Y-m-d format
     * @param int $numberOfPaydates The number of paydates to generate
     * @return array the next paydates (from today) as strings in Y-m-d format
     */
    public function calculatePaydates(string $initialPaydate, int $numberOfPaydates): array;
}
