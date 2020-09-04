<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\HttpKernel\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Psuw\CommonListener\EventListener\ExpressionTriggeredListener;

/**
 * ConvertResponseListener 
 *
 * Response content converter using given converter evaluating configured 
 * expressions in event context if trigger expression is matched.
 * Return value of last expression is treated as content after conversion.
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ConvertResponseListener extends ExpressionTriggeredListener
{
    use \Psuw\CommonListener\Expression\ExpressionEvaluatingTrait 
    {
        setExpressions as protected;
        addExpression as protected;
    }

    protected $converter;

    /**
     * __construct 
     * 
     * @param mixed $trigger 
     * @param string|object $converter 
     * @param array $convExpressions 
     * @return void
     */
    public function __construct($trigger, $converter, array $convExpressions)
    {
        $this->setTrigger($trigger);

        if(!is_object($converter) && !class_exists($converter)) {
            throw new \InvalidArgumentException(
                'Object or existing class name expected!'
            );
        }
        $this->converter = $converter;

        if(empty($convExpressions)) {
            throw new \InvalidArgumentException(
                'Convert expressions required!'
            );
        }
        $this->setExpressions($convExpressions);
    }

    /**
     * onKernelResponse 
     * 
     * @param FilterResponseEvent $event 
     * @param string $eventName
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event, $eventName)
    {
        if(!$this->isTriggered($event, $eventName)) {
            return;
        }
        $results = $this->evaluateExpressions(array(
            'converter' => is_object($this->converter)
                ? $this->converter
                : new $this->converter($event->getResponse()->getContent()),
            'content' => $event->getResponse()->getContent(),
            'event' => $event,
            'event_name' => $eventName,
        ));
        $event
            ->getResponse()
            ->setContent(empty($results) ? null : end($results))
        ;
    }
}
