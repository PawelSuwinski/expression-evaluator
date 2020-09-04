<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\EventListener;

use Psuw\CommonListener\EventListener\ExpressionTriggeredListener;
use Psuw\CommonListener\Tests\HttpKernel\EventListener\ConvertResponseListenerTest;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * ExpressionTriggeredListenerTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ExpressionTriggeredListenerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * testListenerDefaultTrigger 
     * 
     * @return void
     */
    public function testListenerDefaultTrigger()
    {
        $this->assertTrue($this->getListener()->__invoke());
    }

    /**
     * testListenerTriggering 
     * 
     * @dataProvider providerListenerTriggering
     * @param mixed $trigger 
     * @return void
     */
    public function testListenerTriggering($trigger)
    {
        $this->assertTrue($this->callListener($trigger));
    }

    /**
     * providerListenerTriggering 
     * 
     * @return array
     */
    public function providerListenerTriggering()
    {
        return array(
            [true],
            [new \ArrayObject()],
            ['true'],
            [new Expression('true')],
            ['event.getResponse().getContent() == "INITIAL"'],
            
            /**
             * context testing cases 
             */
            ['debug'],
            ['debug && app == "Test"'],
            ['app == "Test" && event.getResponse().getContent() == "INITIAL"'],
        );
    }

    /**
     * testListenerPassingBy 
     * 
     * @dataProvider providerListenerPassingBy
     * @param mixed $trigger 
     * @return void
     */
    public function testListenerPassingBy($trigger)
    {
        $this->assertFalse($this->callListener($trigger));
    }

    /**
     * providerListenerPassingBy 
     * 
     * @return array
     */
    public function providerListenerPassingBy()
    {
        return array(
            [false],
            [null],
            [array()],
            [0],
            ['false'],
            [new Expression('false')],
            ['event.getResponse().getContent() != "INITIAL"'],

            /**
             * context testing cases 
             */
            ['!debug'],
            ['!debug || app != "Test"'],
            ['app != "Test" || event.getResponse().getContent() != "INITIAL"'],
        );
    }

    /**
     * callListener 
     * 
     * @return bool
     */
    protected function callListener($trigger)
    {
        $listener = $this->getListener();
        $listener->setTrigger($trigger);

        return $listener->__invoke();
    }

    /**
     * getListener 
     * 
     * @return ExpressionTriggeredListener
     */
    protected function getListener()
    {
        $listener = $this
            ->getMockBuilder(
                'Psuw\CommonListener\EventListener\ExpressionTriggeredListener'
            )
            ->setMethods(array('__invoke'))
            ->getMock()
        ;
        $listener->setContext(array(
            'debug' => true,
            'app' => 'Test',
        ));
        $listener->method('__invoke')->willReturnCallback(\Closure::bind(
            function() { 
                return $this->isTriggered(
                    ConvertResponseListenerTest::getFilterResponseEvent(),
                    'test_event'
                ); 
            }, 
            $listener,
            $listener
        ));
        return $listener;
    }
}

