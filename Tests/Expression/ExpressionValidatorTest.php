<?php

/**
 * This file is part of the PsuwExpressionEvaluator package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @license MIT
 */

namespace Psuw\ExpressionEvaluator\Tests\Expression;

use Psuw\ExpressionEvaluator\Expression\ExpressionValidator;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * ExpressionValidatorTest.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
class ExpressionValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getInvalidExpressions.
     *
     * @return array
     */
    public static function getInvalidExpressions()
    {
        return [
            [true],
            [false],
            [new \ArrayObject()],
            [10],
        ];
    }

    /**
     * testValidExpression.
     *
     * @dataProvider providerValidExpression
     *
     * @param mixed $expression
     */
    public function testValidExpression($expression)
    {
        $this->assertTrue(ExpressionValidator::isValid($expression));
    }

    /**
     * providerValidExpression.
     *
     * @return array
     */
    public function providerValidExpression()
    {
        return [
            ['event.getSubject()'],
            [new Expression('event.getSubject()')],
        ];
    }

    /**
     * testInvalidExpression.
     *
     * @dataProvider providerInvalidExpression
     *
     * @param mixed $expression
     */
    public function testInvalidExpression($expression)
    {
        $this->assertFalse(ExpressionValidator::isValid($expression));
    }

    /**
     * providerInvalidExpression.
     *
     * @return array
     */
    public function providerInvalidExpression()
    {
        return self::getInvalidExpressions();
    }

    /**
     * testValidationException.
     *
     * @dataProvider providerInvalidExpression
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #string or.*Expression.*expected#
     *
     * @param mixed $expression
     */
    public function testValidationException($expression)
    {
        $this->assertFalse(ExpressionValidator::validate($expression));
    }
}
