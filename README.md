
Coding challenge for xyz.dev 2022-04-29
=======================================

# Install

No third party vendor packages are used, but running `composer install` won't hurt.

## Assumptions

Since specs only mentioned PHP7, assumption was made that the minimum version was PHP 7.0.
As such, features such as nullable type hints are not supported.

Timezones configuration and whatnot is already squared away at system/ini file level.


# Bonuses Included:

 - `MySolution\HolidayGenerator` service to generate holidays dynamically.
 - Additional split into multiple classes to handle pay models separately.
 - Interacticve CLI tool, see `bin/paydatecalc`

# Bonuses not included

 - Unit tests - ran out of time but happy to include if requested.
