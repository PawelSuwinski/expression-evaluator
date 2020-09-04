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
use \Psuw\CommonListener\Expression\ExpressionLanguageAwareInterface;


/**
 * ExpressionEvaluatingTraitTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ExpressionEvaluatingTraitTest extends \PHPUnit\Framework\TestCase 
    implements ExpressionLanguageAwareInterface
{
    use \Psuw\CommonListener\Expression\ExpressionEvaluatingTrait;
    use \Psuw\CommonListener\Expression\ExpressionLanguageAwareTrait;

    /**
     * testAddingExpressionValidationException 
     * 
     * @dataProvider providerInvalidExpression
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #string or.*Expression.*expected#
     * @param mixed $expression 
     * @return void
     */
    public function testAddingExpressionValidationException($expression)
    {
        $this->addExpression($expression);
    }

    /**
     * providerInvalidExpression 
     * 
     * @return array
     */
    public function providerInvalidExpression()
    {
        return ExpressionValidatorTest::getInvalidExpressions();
    }

    /**
     * testSettingExpressionsValidationException 
     * 
     * @dataProvider providerSettingExpressionsValidationException
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #string or.*Expression.*expected#
     * @param array $expression 
     * @return void
     */
    public function testSettingExpressionsValidationException($expressions)
    {
        $this->setExpressions($expressions);
    }

    /**
     * providerSettingExpressionsValidationException 
     * 
     * @return array
     */
    public function providerSettingExpressionsValidationException()
    {
        return array(
            [array('event.getSubject()', true, new Expression('true'))],
            [array(new Expression('event.getSubject()'), false)],
            [array('event.getSubject()', new \ArrayObject(), 'true')],
        );
    }

    /**
     * testExpressionSetting 
     * 
     * @return void
     */
    public function testExpressionSetting()
    {
        $this->assertEmpty($this->expressions);

        $this->addExpression('true');
        $this->assertEquals(array('true'), $this->expressions);

        $this->addExpression('false');
        $this->assertEquals(array('true', 'false'), $this->expressions);

        $expressions = array('true', 'true');
        $this->setExpressions($expressions);
        $this->assertEquals($expressions, $this->expressions);

        $this->addExpression('false');
        $this->assertEquals(
            array_merge($expressions, array('false')),
            $this->expressions
        );

        $this->setExpressions(array());
        $this->assertEmpty($this->expressions);
    }

    /**
     * testEvaluatingExpressionInterfaceException 
     * 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #ExpressionLanguageAwareInterface#
     * @return void
     */
    public function testEvaluatingExpressionInterfaceException()
    {
        $mock = $this->getMockForTrait(
            'Psuw\CommonListener\Expression\ExpressionEvaluatingTrait',
            array(),
            '',
            true,
            true,
            true,
            array('__invoke')
        );
        $mock
            ->method('__invoke')
            ->willReturnCallback(\Closure::bind(
                function(array $context) {
                    return $this->evaluateExpressions($context);
                },
                $mock,
                $mock
            ));
        $mock(array());
    }
    
    /**
     * testEvaluatingEmptyExpressions 
     * 
     * @return void
     */
    public function testEvaluatingEmptyExpressions()
    {
        $this->setExpressions(array());
        $this->assertEquals(array(), $this->evaluateExpressions(array()));
    }

    /**
     * testEvaluatingExpressions 
     * 
     * @return void
     */
    public function testEvaluatingExpressions()
    {
        $context = array(
            'container' => new \ArrayObject(array_flip(array('one', 'two'))),
            'trigger' => true,
        );

        $testExpression = 'result === true ? "OK" : "NOT"';

        /**
         * expression => expected value 
         */
        $expressions = array(
            'trigger && container.offsetExists("one")' => true,
            $testExpression => 'OK',
            '!trigger && container.offsetExists("one")' => false,
            $testExpression => 'NOT',
            'trigger && container.offsetExists("five")' => false,
            $testExpression => 'NOT',
            'trigger && !container.offsetExists("five")' => true, 
            $testExpression => 'OK',
            'container["one"] + container["two"]' => 1, 
            'result + container["two"]' => 2,
            'container["one"] * container["two"]' => 0,
        );

        $this->setExpressions(array_keys($expressions));

        $this->assertEquals(
            array_values($expressions),
            $this->evaluateExpressions($context)
        );
    }
}
