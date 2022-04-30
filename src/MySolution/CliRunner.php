<?php

namespace MySolution;

use MySolution\UserInputValidator;
use MySolution\PaydateCalculator;

/**
 * Excectes CLI mode of paydate calc.
 */
class CliRunner {

  /**
   * task runner.
   */
  public function run() {


    $inputValidator = new UserInputValidator();

    $paydateModel = $this->readLine(
            'Specify paydateModel - One of the paydate model options MONTHLY|BIWEEKLY|WEEKLY :',
            [$inputValidator, 'validatePaydateModel']
    );

    $initialPaydate = $this->readline(
            'Specify initialPaydate - First paydate as a string in Y-m-d format :',
            [$inputValidator, 'validateDate']
    );

    $numberOfPaydates      = $this->readline(
            'Specify the number of paydates to generate :',
            [$inputValidator, 'validatePositiveInt'],
            null,
            'Number of Paydates'
    );
    
    $defaultInitialPayYear = intval(date('Y', strtotime($initialPaydate)));
    $holidayYearStart      = $this->readline(
            'OPTIONAL; start year to generate holiday list, defaults to year of initial pay (' . $defaultInitialPayYear . '):',
            [$inputValidator, 'validatePositiveInt'],
            $defaultInitialPayYear,
            'Start year'
    );

    $holidayYearCount = $this->readline(
            'OPTIONAL; number of years of holiays to generate, defaults to 5 :',
            [$inputValidator, 'validatePositiveInt'],
            5,
            'Number of years'
    );

    $holidayGen = new HolidayGenerator();
    $holidays   = [];

    for ($y = $holidayYearStart; $y <= ($holidayYearStart + $holidayYearCount); $y++) {
      $holidays = array_merge($holidays, $holidayGen->generateHolidays($y));
    }

    $payCalc  = new PaydateCalculator($paydateModel, $holidays);
    $payDates = $payCalc->calculatePaydates($initialPaydate, (int) $numberOfPaydates);

//@todo no fancy layout for you. maybe later.
    $this->outLn('')
            ->outLn('----')
            ->outLn("Initial paydate: {$initialPaydate}, using {$paydateModel} Model, for {$numberOfPaydates} paydates:")
            ->outLn('')
            ->outLn(implode(PHP_EOL, $payDates))
            ->outLn('----');
  }

  /**
   * Reads input from user interactively. Mildly janky.
   * 
   * @param string $prompt
   * @param callable $validator Validator callbable that should return error message ONLY IF valid, otherwise.
   * @param mixed $default default to use if there's no input.
   * @param mixed $validator ..extra args
   * 
   * @return string Valid user input.
   */
  protected function readLine(string $prompt, $validatorFunction = null, $default = null, ...$validatorExtraArgs) {
    do {
      $var = trim(readline($prompt));

      if (0 === strlen($var) && isset($default)) {
        $var = $default;
      }
      var_dump(is_callable($validatorFunction));
      if (!is_callable($validatorFunction)) {
        return $var;
      }

      $error = call_user_func($validatorFunction, $var, ...$validatorExtraArgs);

      if (!empty($error)) {
        $this->outLn('! User input validation occured:')
                ->outLn(' -- ' . $error)
                ->outLn('Enter input again or CRTL+C to exit.');
      }
    }
    while (!empty($error));

    return $var;
  }

  /**
   * Outputs a message.
   * 
   * @param string $msg A message.
   * 
   * @return static Self, for chaining.
   */
  protected function outLn(string $msg) {
    echo $msg, PHP_EOL;
    return $this;
  }

}
