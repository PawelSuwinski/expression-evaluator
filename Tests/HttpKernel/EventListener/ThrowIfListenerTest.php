<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\HttpKernel\EventListener;

use Psuw\CommonListener\HttpKernel\EventListener\ThrowIfListener;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * ThrowIfListenerTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ThrowIfListenerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * testInvalidExceptionClass 
     *
     * @dataProvider providerInvalidExceptionClass 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #expected to be \\Exception derivation#
     * @param string $class 
     * @return void
     */
    public function testInvalidExceptionClass($class)
    {
        $this->getListener(true, null, $class);
    }
   
    /**
     * providerInvalidExceptionClass 
     * 
     * @return array
     */
    public function providerInvalidExceptionClass()
    {
        return array(
            ['Excception'],
            ['\ArrayObject'],
            ['\ArrrayObject'],
        );
    }

    /**
     * testExceptionTriggering 
     * 
     * @dataProvider providerExceptionTriggering
     * @param mixed $expression 
     * @param string $message 
     * @param string $class 
     * @return void
     */
    public function testExceptionTriggering($expression, $message = null, $class = null)
    {
        $this->expectException($class ?: 'Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException');
        if($message !== null) {
            $this->expectExceptionMessage($message);
        }
        $this->testExceptionPassingBy($expression, $message, $class);
    }
    
    /**
     * providerExceptionTriggering 
     * 
     * @return array
     */
    public function providerExceptionTriggering()
    {
        return array(
            [true, 'EXCEPTION', 'Exception'],
            [true, null, 'Exception'],
            [new \stdClass(), 'EXCEPTION', 'Exception'],
            [true, 'EXCEPTION', null],
            ['event.getResponse().getContent() == "INITIAL"', 'EXCEPTION'],
            [
                new Expression('event.getResponse().getContent() == "INITIAL"'),
                'EXCEPTION',
            ],
        );
    }

    /**
     * testExceptionPassingBy 
     * 
     * @dataProvider providerExceptionPassingBy
     * @doesNotPerformAssertions
     * @param mixed $expression 
     * @return void
     */
    public function testExceptionPassingBy($expression, $message = null, $class = null) 
    {
        $this->getListener($expression, $message, $class)->onEvent(
            ConvertResponseListenerTest::getFilterResponseEvent(),
            'test_event'
        );
    }

    /**
     * providerExceptionPassingBy 
     * 
     * @return array
     */
    public function providerExceptionPassingBy()
    {
        return array(
            [false],
            [array()],
            ['event.getResponse().getContent() != "INITIAL"'],
            [new Expression('event.getResponse().getContent() != "INITIAL"')],
        );
    }

    /**
     * getListener 
     * 
     * @param mixed $expression 
     * @return ThrowIfListener
     */
    protected function getListener($expression, $message = null, $class =null)
    {
        return new ThrowIfListener($expression, $message, $class);
    }
}

