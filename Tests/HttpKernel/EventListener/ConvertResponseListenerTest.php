<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\HttpKernel\EventListener;

use Psuw\CommonListener\HttpKernel\EventListener\ConvertResponseListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/**
 * ConvertResponseListenerTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ConvertResponseListenerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getFilterResponseEvent 
     * 
     * @param Response $response 
     * @return FilterResponseEvent
     */
    static public function getFilterResponseEvent(Response $response = null) 
    {
        return new FilterResponseEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response ?: new Response('INITIAL')
        );
    }

    /**
     * getInvalidFormatExpressions 
     * 
     * @return array
     */
    static public function getInvalidFormatExpressions()
    {
        return array(
            array([new Expression(''), true, new Expression('')]),
            array([new Expression(''), new \ArrayObject()]),
            array([false]),
        );
    }

    /**
     * testInvalidConverterException 
     *
     * @dataProvider providerInvalidConverterExceptionException
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #Object or existing class name expected#
     * @param mixed $converter
     * @return void
     */
    public function testInvalidConverterExceptionException($converter)
    {
        new ConvertResponseListener(true, $converter, array());
    }
    
    /**
     * providerInvalidConverterExceptionException 
     * 
     * @return array
     */
    public function providerInvalidConverterExceptionException()
    {
        return array(
            array(mt_rand()),
            array('ArrrayObject'),
            array(true),
        );
    }

    /**
     * testExpressionsRequiredException 
     * 
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #expressions required#
     * @return void
     */
    public function testExpressionsRequiredException()
    {
        new ConvertResponseListener(true, 'ArrayObject', array());
    }

    /**
     * testExpressionInvalidFormatException 
     * 
     * @dataProvider providerExpressionInvalidFormatException
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp #string or.*Expression.*expected#
     * @param array $expressions 
     * @return void
     */
    public function testExpressionInvalidFormatException(array $expressions)
    {
        new ConvertResponseListener(true, 'ArrayObject', $expressions);
    }

    /**
     * providerExpressionInvalidFormatException 
     * 
     * @return array
     */
    public function providerExpressionInvalidFormatException()
    {
        return self::getInvalidFormatExpressions();
    }

    /**
     * testResponseConvertion 
     * 
     * @dataProvider providerResponseConvertion
     * @param mixed $trigger 
     * @param mixed $conv 
     * @param array $exp 
     * @param string $content 
     * @return void
     */
    public function testResponseConvertion($trigger, $conv, $exp, $content)
    {
        $event = self::getFilterResponseEvent();
        $listener = new ConvertResponseListener($trigger, $conv, $exp);
        $listener->onKernelResponse($event, 'test_event');

        $this->assertEquals($content, $event->getResponse()->getContent());
    }

    /**
     * providerResponseConvertion 
     * 
     * @return array
     */
    public function providerResponseConvertion()
    {
        $conv = new \ArrayObject(array('C0', 'C1', 'C2'));
        return array(
            /**
             * trigger = false
             */
            array(
                false, 
                $conv,
                ['converter.offsetGet(1)'],
                'INITIAL',
            ),
            /**
             * trigger as expression  = false
             */
            array(
                'event.getResponse().getContent() != "INITIAL"', 
                $conv,
                ['converter.offsetGet(1)'],
                'INITIAL',
            ),
            /**
             * trigger = true 
             */
            array(
                true, 
                $conv,
                ['converter.offsetGet(1)'],
                'C1',
            ),
            /**
             * trigger as expression  = true
             */
            array(
                new Expression('event.getResponse().getContent() == "INITIAL"'), 
                $conv,
                ['converter.offsetGet(1)'],
                'C1',
            ),
            array(
                'event.getResponse().getContent() == "INITIAL"', 
                $conv,
                ['converter.offsetGet(1)'],
                'C1',
            ),
            /**
             *  Chain of expression, only last one give the response content
             */
            array(
                true, 
                $conv,
                [
                    'converter.offsetGet(1)', 
                    'converter.offsetGet(2)', 
                    'converter.offsetGet(0)',
                ],
                'C0',
            ),
            /**
             *  Chain of expression, only last one give the response content
             */
            array(
                true, 
                $conv,
                [
                    'converter.offsetGet(1)', 
                    new Expression('converter.offsetGet(2)'), 
                ],
                'C2',
            ),
        );
    }
}
