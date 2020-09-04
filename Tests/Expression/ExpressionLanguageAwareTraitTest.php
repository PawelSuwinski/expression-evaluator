<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


/**
 * ExpressionLanguageAwareTraitTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ExpressionLanguageAwareTraitTest extends \PHPUnit\Framework\TestCase
{
    use \Psuw\CommonListener\Expression\ExpressionLanguageAwareTrait;

    /**
     * testGetExpressionLanguageIfNotSet 
     * 
     * @return void
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
     * testGetExpressionLanguageIfSet 
     * 
     * @return void
     */
    public function testGetExpressionLanguageIfSet()
    {
        $this->assertNull($this->expressionLanguage);
        $expressionLanguage = new ExpressionLanguage();
        $this->setExpressionLanguage($expressionLanguage);
        $this->assertSame($expressionLanguage, $this->getExpressionLanguage());
    }
}
