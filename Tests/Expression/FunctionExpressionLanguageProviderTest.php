<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2019 Pawe³ Suwiñski
 * @author Pawe³ Suwiñski <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\Expression;

use Psuw\CommonListener\Expression\FunctionExpressionLanguageProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


/**
 * FunctionExpressionLanguageProviderTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2019, Pawe³ Suwiñski
 * @author Pawe³ Suwiñski <psuw@wp.pl> 
 * @license MIT
 */
class FunctionExpressionLanguageProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * language 
     * 
     * @var Symfony\Component\ExpressionLanguage\ExpressionLanguage 
     */
    protected $language;

    /**
     * context 
     * 
     * @var array
     */
    protected $context = array(
        'values' => array('k1' => 'v1', 'k2' => 'v2'),
        'name' => 'First Name',
    );

    /**
     * {@inheritDoc}
     */
    public function setUp() 
    {
        $this->language = new ExpressionLanguage();
        $this->language->registerProvider(
            new FunctionExpressionLanguageProvider(array(
                 'is_array',
                 'has_key' => 'array_key_exists',
                 'lower' => 'strtolower',
                 'gettype',
             ))
        );
     }

    /**
     * testEvaluation 
     * 
     * @dataProvider providerEvaluation
     * @param mixed $expected 
     * @param string $expression 
     * @return void
     */
    public function testEvaluation($expected, $expression)
    {
        $this->assertEquals(
            $expected, 
            $this->language->evaluate($expression, $this->context)
        );

    }
    
    /**
     * providerEvaluation 
     * 
     * @return array
     */
    public function providerEvaluation()
    {
        return array(
            [true, 'is_array(values)'],
            [true, 'is_array(values) && has_key("k2", values)'],
            ['v2', 'values["k2"]'],
            [false, 'is_array(values) && has_key("k3", values)'],
            ['First Name', 'name'],
            ['first name', 'lower(name)'],
            ['string', 'gettype(name)'],
            ['array', 'gettype(values)'],
        );
    }

    /**
      * testCompilation 
      *
      * @dataProvider providerCompilation
      * @param string $expected 
      * @param string $expression 
      * @return void
      */
    public function testCompilation($expected, $expression)
    {
        $this->assertEquals(
            $expected, 
            $this->language->compile($expression, array_keys($this->context))
        );
    }

    /**
     * providerCompilation 
     * 
     * @return array
     */
    public function providerCompilation()
    {
        return array(
            ['is_array($values)', 'is_array(values)'],
            ['strtolower($name)', 'lower(name)'],
            ['array_key_exists("k2", $values)', 'has_key("k2", values)'],
            [
                '(array_key_exists("k2", $values) && strtolower($name))', 
                'has_key("k2", values) && lower(name)',
            ],
        );
    }

}

