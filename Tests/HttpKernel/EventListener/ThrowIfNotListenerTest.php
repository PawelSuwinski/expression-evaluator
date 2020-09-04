<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Tests\HttpKernel\EventListener;

use Psuw\CommonListener\HttpKernel\EventListener\ThrowIfNotListener;

/**
 * ThrowIfNotListenerTest 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ThrowIfNotListenerTest extends ThrowIfListenerTest
{
    /**
     * providerExceptionTriggering 
     * 
     * @return array
     */
    public function providerExceptionTriggering()
    {
        return parent::providerExceptionPassingBy();
    }

    /**
     * providerExceptionPassingBy 
     * 
     * @return array
     */
    public function providerExceptionPassingBy()
    {
        return parent::providerExceptionTriggering();
    }

    /**
     * getListener 
     * 
     * @param mixed $expression 
     * @param mixed $message 
     * @param mixed $class 
     * @return ThrowIfNotListener
     */
    protected function getListener($expression, $message = null, $class =null)
    {
        return new ThrowIfNotListener($expression, $message, $class);
    }
}
