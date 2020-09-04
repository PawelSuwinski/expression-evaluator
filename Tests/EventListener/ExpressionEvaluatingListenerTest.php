<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\EventListener;

use Psuw\CommonListener\EventListener\ExpressionEvaluatingListener;
use Psuw\CommonListener\Tests\HttpKernel\EventListener\ConvertResponseListenerTest;

/**
 * ExpressionEvaluatingListenerTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ExpressionEvaluatingListenerTest extends \PHPUnit\Framework\TestCase
{
    static protected $listener;

    /**
     * setUpBeforeClass 
     * 
     * @return void
     */
    static public function setUpBeforeClass()
    {
        self::$listener = new ExpressionEvaluatingListener(array(
            'container' => new \ArrayObject(array_flip(array('one', 'two'))),
         ));
    }

    /**
     * testEventProcessing 
     * 
     * @dataProvider providerEventProcessing
     * @param string $trigger 
     * @param array $expressions 
     * @param string $expected 
     * @return void
     */
    public function testEventProcessing($trigger, array $expressions, $expected)
    {
        self::$listener->setTrigger($trigger);
        self::$listener->setExpressions($expressions);
        $event = ConvertResponseListenerTest::getFilterResponseEvent();
        self::$listener->onEvent($event, 'test_event');

        $this->assertEquals($expected, $event->getResponse()->getContent());
    }

    /**
     * providerEventProcessing 
     * 
     * @return array
     */
    public function providerEventProcessing()
    {
        return array(
            array(
                'event.getResponse().getContent() == "INITIAL"',
                array(
                    'container["one"] + container["two"]', 
                    'result + 1', 
                    'result * 2', 
                    'event.getResponse().setContent(result)',
                ),
                '4'
            ),
            array(
                'event.getResponse().getContent() != "INITIAL"',
                array(
                    'container["one"] + container["two"]', 
                    'event.getResponse().setContent(result)',
                ),
                'INITIAL'
            ),
            array(
                true, 
                array('event.getResponse().setContent("FINAL")'), 
                'FINAL'
            ),
        );
    }
}
