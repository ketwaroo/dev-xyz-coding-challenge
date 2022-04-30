
Coding challenge for xyz.dev 2022-04-29
=======================================

# Install

run `composer install` to get various patkages set up and autoload configured.


## Assumptions

Since specs only mentioned PHP7, assumption was made that the minimum version was PHP 7.0.
As such, features such as nullable type hints are not supported.

Timezones configuration and whatnot is already squared away at system/ini file level.


# Bonuses Included:

 - `MySolution\PaydateCalculator` instead of `MyPaydateCalculator` because namespacing takes care of the `My`.
 - `MySolution\HolidayGenerator` service to generate holidays dynamically.
 - Additional split into multiple classes to handle pay models separately.
 - Interacticve CLI tool, see `bin/paydatecalc`
 - Unit tests. a little bit

# Unit tests

The expectations of what counts as unit tests vary between companies, teams, individual developers, etc.
My perception of unit is the smallest testable portion of a class without external dependencies,
i.e. the method.

I did include one minimal functional test.

I can always adapt to what unit test expectations are for existing standards within the teams though.

to run tests

```
./vendor/bin/phpunit test
```


