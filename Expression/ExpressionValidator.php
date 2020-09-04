<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Pawe³ Suwiñski
 * @author Pawe³ Suwiñski <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Expression;

use Symfony\Component\ExpressionLanguage\Expression;


/**
 * ExpressionValidator 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Pawe³ Suwiñski
 * @author Pawe³ Suwiñski <psuw@wp.pl> 
 * @license MIT
 */
class ExpressionValidator
{
    /**
     * validate 
     * 
     * @throw \InvalidArgumentException on invalid format
     * @param mixed $expression 
     * @return void
     */
    static public function validate($expression) 
    {
        if(!self::isValid($expression)) { 
            throw new \InvalidArgumentException(
                'string or "Symfony\Component\ExpressionLanguage\Expression" '.
                    'instance expected as expression!'
            );
        }
    }

    /**
     * isValid 
     * 
     * @param mixed $expression 
     * @return bool
     */
    static public function isValid($expression)
    {
        return is_string($expression) || $expression instanceof Expression;
    }
}

