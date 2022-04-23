<?php

/**
 * This file is part of the PsuwExpressionEvaluator package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @license MIT
 */

namespace Psuw\ExpressionEvaluator\Tests\Expression;

use Psuw\ExpressionEvaluator\Expression\ExpressionLanguageAwareInterface;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * ExpressionEvaluatingTraitTest.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
class ExpressionEvaluatingTraitTest extends \PHPUnit\Framework\TestCase implements ExpressionLanguageAwareInterface
{
    use \Psuw\ExpressionEvaluator\Expression\ExpressionEvaluatingTrait;
    use \Psuw\ExpressionEvaluator\Expression\ExpressionLanguageAwareTrait;

    /**
     * testAddingExpressionValidationException.
     *
     * @dataProvider providerInvalidExpression
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #string or.*Expression.*expected#
     *
     * @param mixed $expression
     */
    public function testAddingExpressionValidationException($expression)
    {
        $this->addExpression($expression);
    }

    /**
     * providerInvalidExpression.
     *
     * @return array
     */
    public function providerInvalidExpression()
    {
        return ExpressionValidatorTest::getInvalidExpressions();
    }

    /**
     * testSettingExpressionsValidationException.
     *
     * @dataProvider providerSettingExpressionsValidationException
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #string or.*Expression.*expected#
     *
     * @param array $expressions
     */
    public function testSettingExpressionsValidationException($expressions)
    {
        $this->setExpressions($expressions);
    }

    /**
     * providerSettingExpressionsValidationException.
     *
     * @return array
     */
    public function providerSettingExpressionsValidationException()
    {
        return [
            [['event.getSubject()', true, new Expression('true')]],
            [[new Expression('event.getSubject()'), false]],
            [['event.getSubject()', new \ArrayObject(), 'true']],
        ];
    }

    /**
     * testExpressionSetting.
     */
    public function testExpressionSetting()
    {
        $this->assertEmpty($this->expressions);

        $this->addExpression('true');
        $this->assertEquals(['true'], $this->expressions);

        $this->addExpression('false');
        $this->assertEquals(['true', 'false'], $this->expressions);

        $expressions = ['true', 'true'];
        $this->setExpressions($expressions);
        $this->assertEquals($expressions, $this->expressions);

        $this->addExpression('false');
        $this->assertEquals(array_merge($expressions, ['false']), $this->expressions);

        $this->setExpressions([]);
        $this->assertEmpty($this->expressions);
    }

    /**
     * testEvaluatingExpressionInterfaceException.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #ExpressionLanguageAwareInterface#
     */
    public function testEvaluatingExpressionInterfaceException()
    {
        $mock = $this->getMockForTrait(
            'Psuw\ExpressionEvaluator\Expression\ExpressionEvaluatingTrait',
            [],
            '',
            true,
            true,
            true,
            ['__invoke']
        );
        $mock
            ->method('__invoke')
            ->willReturnCallback(\Closure::bind(
                function (array $context) {
                    return $this->evaluateExpressions($context);
                },
                $mock,
                $mock
            ));
        $mock([]);
    }

    /**
     * testEvaluatingEmptyExpressions.
     */
    public function testEvaluatingEmptyExpressions()
    {
        $this->setExpressions([]);
        $this->assertEquals([], $this->evaluateExpressions([]));
    }

    /**
     * testEvaluatingExpressions.
     */
    public function testEvaluatingExpressions()
    {
        $context = [
            'container' => new \ArrayObject(array_flip(['one', 'two'])),
            'trigger' => true,
        ];

        $testExpression = 'result === true ? "OK" : "NOT"';

        /**
         * expression => expected value.
         */
        $expressions = [
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
        ];

        $this->setExpressions(array_keys($expressions));

        $this->assertEquals(array_values($expressions), $this->evaluateExpressions($context));
    }
}
