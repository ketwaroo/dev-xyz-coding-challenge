<?php

use DevXyz\Challenge\PaydateCalculatorInterface;
use MySolution\PaydateCalculator;
use MySolution\HolidayGenerator;

require './vendor/autoload.php';

//Paydate Models:
//
//MONTHLY A person is paid on the same day of the month every month, for instance, 1/17/2012 and 2/17/2012
//BIWEEKLY A person is paid on the same day of the week every other week, for instance, 4/6/2012 and 4/20/2012
//WEEKLY A person is paid on the same day of the week every week, for instance 4/9/2012 and 4/16/2012 
// generate holidays for a few years.

$holidays = [];

$hol = new HolidayGenerator();
for ($y = 2013; $y < 2030; $y++) {
  // it'd be easier to pass the holiday generator to the pay caculator. 
  $holidays = array_merge($holidays, $hol->generateHolidays($y));
}

$paydateModel = '';

$payDateCalc = new PaydateCalculator(PaydateCalculatorInterface::PAYDATE_MODEL_MONTHLY, $holidays);

print_r($payDateCalc->calculatePaydates('2014-01-17', 5));

