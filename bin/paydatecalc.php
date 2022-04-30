<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MySolution\HolidayGenerator;
use MySolution\PaydateCalculator;

$paydateModel     = readline('Specify paydateModel - One of the paydate model options MONTHLY|BIWEEKLY|WEEKLY :');
$initialPaydate   = readline('Specify initialPaydate - First paydate as a string in Y-m-d format :'); // @todo format validation? it's getting late though.
$numberOfPaydates = readline('Specify the number of paydates to generate :');

$defaultInitialPayDate = intval(date('Y', strtotime($initialPaydate)));
$holidayYearStart = readline('OPTIONAL; start year to generate holiday list, defaults to year of initial pay (' . $defaultInitialPayDate . '):');
$holidayYearStart = empty($holidayYearStart) ? $defaultInitialPayDate : $holidayYearStart;

$holidayYearCount = readline('OPTIONAL; number of years of holiays to generate, defaults to 5 :');
$holidayYearCount = empty($holidayYearCount)?5:$holidayYearCount;

$holidayGen = new HolidayGenerator();
$holidays   = [];

for ($y = $holidayYearStart; $y <= ($holidayYearStart + $holidayYearCount); $y++) {
  $holidays = array_merge($holidays, $holidayGen->generateHolidays($y));
}

$payCalc  = new PaydateCalculator($paydateModel, $holidays);
$payDates = $payCalc->calculatePaydates($initialPaydate, (int) $numberOfPaydates);

//@todo no fancy layout for you. maybe later.
echo PHP_EOL, '----', PHP_EOL;
echo 'Initial paydate: ', $initialPaydate, ', using ', $paydateModel, ' Model, for ', $numberOfPaydates, ' paydates:';
echo PHP_EOL, '----', PHP_EOL;
echo implode(PHP_EOL, $payDates);
echo PHP_EOL, '----', PHP_EOL;
