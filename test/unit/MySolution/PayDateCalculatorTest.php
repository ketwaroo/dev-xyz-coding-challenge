<?php

namespace Test\unit;

use PHPUnit\Framework\TestCase;
use DevXyz\Challenge\PaydateCalculatorInterface;
use MySolution\PaydateCalculator;
use MySolution\UserInputValidator;
use MySolution\PaydateModel\PaydateModelInterface;

/**
 * PayDateCalculatorTest
 * 
 * Testing only interface defined methods at this point.
 * 
 * @covers MySolution\PaydateCalculator
 */
class PayDateCalculatorTest extends TestCase {

  /**
   * Test class init.
   */
  public function testConstruct() {

    // setup
    $mock = $this->createPartialMock(PaydateCalculator::class, [
        'newUserInputValidator',
    ]);

    $mockValidator = $this->createPartialMock(UserInputValidator::class, [
        'validatePaydateModel',
        'validateHolidays',
    ]);

    $paydateModel = '2045-02-05';
    $holidays     = ['2045-02-06'];

    // expect
    $mock->expects($this->once())
            ->method('newUserInputValidator')
            ->willReturn($mockValidator);

    $mockValidator->expects($this->once())
            ->method('validatePaydateModel')
            ->with($paydateModel)
            ->willReturn(null);
    $mockValidator->expects($this->once())
            ->method('validateHolidays')
            ->with($holidays)
            ->willReturn(null);

    // assert

    $mock->__construct($paydateModel, $holidays);

    // phpunit removed readAttribute in newer versions it seems...
    $this->assertSame($holidays, $mock->getHolidays());
    $this->assertSame($paydateModel, $mock->getPaydateModel());
  }

  /**
   * Test class init with invalid params.
   */
  public function testConstruct_InvalidArgumentException() {


    // setup
    $mock = $this->createPartialMock(PaydateCalculator::class, [
        'newUserInputValidator',
    ]);

    $mockValidator = $this->createPartialMock(UserInputValidator::class, [
        'validatePaydateModel',
        'validateHolidays',
    ]);

    $paydateModel = '2045-02-05';
    $holidays     = ['2045-02-06'];

    // expect
    $mock->expects($this->once())
            ->method('newUserInputValidator')
            ->willReturn($mockValidator);

    $mockValidator->expects($this->once())
            ->method('validatePaydateModel')
            ->with($paydateModel)
            ->willReturn('foo');
    $mockValidator->expects($this->once())
            ->method('validateHolidays')
            ->with($holidays)
            ->willReturn('bar');

    $this->expectException(\InvalidArgumentException::class);
    // should contain error messages from validator
    $this->expectExceptionMessageMatches('/foo/');
    $this->expectExceptionMessageMatches('/bar/');

    // assert    
    $mock->__construct($paydateModel, $holidays);
  }

  /**
   * Tests calculatePaydates().
   */
  public function testCalculatePaydates() {
    // setup
    $mock = $this->createPartialMock(PaydateCalculator::class, [
        'newPaydateModelService',
        'getPaydateModel',
        'getValidator',
    ]);

    $mockValidator = $this->createPartialMock(UserInputValidator::class, [
        'validateDate',
        'validatePositiveInt',
    ]);

    $mockPayModel = $this->createPartialMock(PaydateModelInterface::class, [
        'calculatePaydates',
    ]);

    // shallow test where we avoid a date that is current date.
    $initialPaydate   = '2014-02-05';
    $numberOfPaydates = 42;
    $payModelSchema   = 'MONTHLY';

    // we're testing the code flow, actual values not that important.
    $expected = [
        'foobar',
    ];

    // expect
    $mock->expects($this->once())
            ->method('getValidator')
            ->willReturn($mockValidator);

    $mockValidator->expects($this->once())
            ->method('validateDate')
            ->with($initialPaydate)
            ->willReturn(null);

    $mockValidator->expects($this->once())
            ->method('validatePositiveInt')
            ->with($numberOfPaydates, 'Number of paydates')
            ->willReturn(null);

    $mock->expects($this->once())
            ->method('getPaydateModel')
            ->willReturn($payModelSchema);

    $mock->expects($this->once())
            ->method('newPaydateModelService')
            ->with($payModelSchema)
            ->willReturn($mockPayModel);

    $mockPayModel->expects($this->once())
            ->method('calculatePaydates')
            ->with($initialPaydate, $numberOfPaydates)
            ->willReturn($expected);

    // assert

    $actual = $mock->calculatePaydates($initialPaydate, $numberOfPaydates);

    $this->assertSame($expected, $actual);
  }

  /**
   * Tests calculatePaydates() on exception codnitions.
   */
  public function testCalculatePaydates_InvalidArgumentException() {
    // setup
    $mock = $this->createPartialMock(PaydateCalculator::class, [
        'newPaydateModelService',
        'getPaydateModel',
        'getValidator',
    ]);

    $mockValidator = $this->createPartialMock(UserInputValidator::class, [
        'validateDate',
        'validatePositiveInt',
    ]);

    $mockPayModel = $this->createPartialMock(PaydateModelInterface::class, [
        'calculatePaydates',
    ]);

    // shallow test where we avoid a date that is current date.
    $initialPaydate   = '2014-02-05';
    $numberOfPaydates = 42;
    $payModelSchema   = 'MONTHLY';

    // we're testing the code flow, actual values not that important.
    $expected = [
        'foobar',
    ];

    // expect

    $mock->expects($this->once())
            ->method('getValidator')
            ->willReturn($mockValidator);

    $mockValidator->expects($this->once())
            ->method('validateDate')
            ->with($initialPaydate)
            ->willReturn('foo');

    $mockValidator->expects($this->once())
            ->method('validatePositiveInt')
            ->with($numberOfPaydates, 'Number of paydates')
            ->willReturn('bar');

    $this->expectException(\InvalidArgumentException::class);
    // should contain error messages from validator
    $this->expectExceptionMessageMatches('/foo/');
    $this->expectExceptionMessageMatches('/bar/');

    $mock->expects($this->never())
            ->method('getPaydateModel');

    $mock->expects($this->never())
            ->method('newPaydateModelService');

    $mockPayModel->expects($this->never())
            ->method('calculatePaydates');

    // assert
    $actual = $mock->calculatePaydates($initialPaydate, $numberOfPaydates);
  }

}
