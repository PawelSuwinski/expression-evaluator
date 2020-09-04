<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\EventListener;

use Psuw\CommonListener\Expression\ExpressionValidator;
use Psuw\CommonListener\Expression\ExpressionLanguageAwareInterface;
use Symfony\Component\EventDispatcher\Event;


/**
 * ExpressionTriggeredListener 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
abstract class ExpressionTriggeredListener implements 
    ExpressionLanguageAwareInterface
{
    use \Psuw\CommonListener\Expression\ExpressionLanguageAwareTrait;

    protected $trigger = true;

    protected $context;

    /**
     * setTrigger 
     * 
     * @param mixed $trigger 
     * @return void
     */
    public function setTrigger($trigger) 
    {
        $this->trigger = $trigger;
    }

    /**
     * setContext 
     * 
     * @param array $context 
     * @return void
     */
    public function setContext(array $context)
    {
        $this->context = $context;
    }

    /**
     * isTriggered 
     * 
     * @param Event $event 
     * @param string $eventName
     * @return bool
     */
    protected function isTriggered(Event $event, $eventName)
    {
        return (ExpressionValidator::isValid($this->trigger) 
            && !$this->getExpressionLanguage()->evaluate(
                $this->trigger,
                isset($this->context) 
                    ? array_merge(
                        $this->context, 
                        array('event' => $event, 'event_name' => $eventName)
                    )
                    : array('event' => $event, 'event_name' => $eventName)
            )
        ) || !$this->trigger ? false : true;
    }
}
