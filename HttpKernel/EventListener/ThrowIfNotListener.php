<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\HttpKernel\EventListener;

use Symfony\Component\EventDispatcher\Event;


/**
 * ThrowIfNotListener 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class ThrowIfNotListener extends ThrowIfListener
{
    /**
     * {@inheritdoc}
     */
    protected function isTriggered(Event $event, $eventName)
    {
        return !parent::isTriggered($event, $eventName);
    }
}
