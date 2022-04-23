<?php

/**
 * This file is part of the PsuwExpressionEvaluator package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @license MIT
 */

namespace Psuw\ExpressionEvaluator\Tests\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * ExpressionLanguageAwareTraitTest.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
class ExpressionLanguageAwareTraitTest extends \PHPUnit\Framework\TestCase
{
    use \Psuw\ExpressionEvaluator\Expression\ExpressionLanguageAwareTrait;

    /**
     * testGetExpressionLanguageIfNotSet.
     */
    public function testGetExpressionLanguageIfNotSet()
    {
        $this->assertNull($this->expressionLanguage);
        $this->assertInstanceOf(
            'Symfony\Component\ExpressionLanguage\ExpressionLanguage',
            $this->getExpressionLanguage()
        );
    }

    /**
     * testGetExpressionLanguageIfSet.
     */
    public function testGetExpressionLanguageIfSet()
    {
        $this->assertNull($this->expressionLanguage);
        $expressionLanguage = new ExpressionLanguage();
        $this->setExpressionLanguage($expressionLanguage);
        $this->assertSame($expressionLanguage, $this->getExpressionLanguage());
    }
}
