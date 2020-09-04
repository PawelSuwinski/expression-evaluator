<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\Expression;

use Symfony\Component\ExpressionLanguage\Expression;
use Psuw\CommonListener\Expression\ExpressionValidator;


/**
 * ExpressionValidatorTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ExpressionValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getInvalidExpressions 
     * 
     * @return array
     */
    static public function getInvalidExpressions()
    {
        return array(
                [true],
                [false],
                [new \ArrayObject()],
                [10],
            );
    }

    /**
     * testValidExpression 
     * 
     * @dataProvider providerValidExpression
     * @param mixed $expression 
     * @return void
     */
    public function testValidExpression($expression)
    {
        $this->assertTrue(ExpressionValidator::isValid($expression));
    }

    /**
     * providerValidExpression 
     * 
     * @return array
     */
    public function providerValidExpression()
    {
        return array(
            ['event.getSubject()'],
            [new Expression('event.getSubject()')],
        );
    }

    /**
     * testInvalidExpression 
     * 
     * @dataProvider providerInvalidExpression
     * @param mixed $expression 
     * @return void
     */
    public function testInvalidExpression($expression)
    {
        $this->assertFalse(ExpressionValidator::isValid($expression));
    }

    /**
     * providerInvalidExpression 
     * 
     * @return array
     */
    public function providerInvalidExpression()
    {
        return self::getInvalidExpressions();
    }

    /**
     * testValidationException 
     * 
     * @dataProvider providerInvalidExpression
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #string or.*Expression.*expected#
     * @param mixed $expression 
     * @return void
     */
    public function testValidationException($expression)
    {
        $this->assertFalse(ExpressionValidator::validate($expression));
    }
}
