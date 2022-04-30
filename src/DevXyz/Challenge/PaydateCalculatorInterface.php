<?php

namespace DevXyz\Challenge;

interface PaydateCalculatorInterface
{
    const PAYDATE_MODEL_MONTHLY = 'MONTHLY';
    const PAYDATE_MODEL_BIWEEKLY = 'BIWEEKLY';
    const PAYDATE_MODEL_WEEKLY = 'WEEKLY';

    /**
     * @param string $paydateModel one of the paydate model options MONTHLY|BIWEEKLY|WEEKLY
     * @param array $holidays list of holidays; make sure to provide holidays here
     * @throws \InvalidArgumentException
     */
    public function __construct(string $paydateModel, array $holidays = []);

    /**
     * takes a paydate model and first paydate and generates the next $numberOfPaydates paydates.
     *
     * @param string $initialPaydate First paydate as a string in Y-m-d format
     * @param int $numberOfPaydates The number of paydates to generate
     *
     * @throws \InvalidArgumentException
     *
     * @return array the next paydates (from today) as strings in Y-m-d format
     */
    public function calculatePaydates(string $initialPaydate, int $numberOfPaydates): array;
}
