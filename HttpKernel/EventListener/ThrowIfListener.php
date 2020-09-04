<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\HttpKernel\EventListener;

use Psuw\CommonListener\EventListener\ExpressionTriggeredListener;
use Symfony\Component\EventDispatcher\Event;


/**
 * ThrowIfListener 
 *
 * @see self::onEvent()
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ThrowIfListener extends ExpressionTriggeredListener
{
    protected $message;

    protected $exceptionClass = 'Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException';

    /**
     * __construct 
     * 
     * @param mixed $trigger 
     * @param string $message 
     * @param string $exceptionClass 
     * @return void
     */
    public function __construct($trigger, $message = null, $exceptionClass = null)
    {
        $this->setTrigger($trigger);

        if(!is_null($message)) {
            $this->message = $message;
        }

        if(!is_null($exceptionClass)) {
            if(!class_exists($exceptionClass) 
                    || (!is_subclass_of($exceptionClass, '\Exception') 
                    && $exceptionClass != 'Exception')) {
                throw new \InvalidArgumentException(
                    '$exceptionClass expected to be \Exception derivation!'
                );
            }
            $this->exceptionClass = $exceptionClass;
        }
    }

    /**
     * onEvent 
     *
     * If expression is a string or an instance of Expression than evaluate 
     * value with ExpressionLanguage passing event as variable. 
     * 
     * @param Event $event 
     * @param string $eventName
     * @return void
     */
    public function onEvent(Event $event, $eventName)
    {
        if($this->isTriggered($event, $eventName)) {
            throw new $this->exceptionClass($this->message);
        }
    }
}

