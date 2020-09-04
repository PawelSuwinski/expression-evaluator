<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\EventListener;

use Symfony\Component\EventDispatcher\Event;

/**
 * ExpressionEvaluatingListener 
 *
 * Listener evaluates configured expressions in given context if trigger 
 * expression is matched and returns expressions results.
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ExpressionEvaluatingListener extends ExpressionTriggeredListener
{
    use \Psuw\CommonListener\Expression\ExpressionEvaluatingTrait;

    /**
     * context 
     * 
     * @var array
     */
    protected $context = array();

    /**
     * __construct 
     * 
     * @param array $context  
     * @return void
     */
    public function __construct(array $context)
    {
        $this->context = $context;
    }

    /**
     * onEvent 
     * 
     * @param Event $event 
     * @param string $eventName
     * @return array
     */
    public function onEvent(Event $event, $eventName)
    {
        if($this->isTriggered($event, $eventName)) {
            $this->evaluateExpressions(array_merge(
                $this->context, 
                array('event' => $event, 'event_name' => $eventName)
            ));
        }
    }
}
