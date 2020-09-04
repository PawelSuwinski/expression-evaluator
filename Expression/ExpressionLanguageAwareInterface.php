<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * ExpressionLanguageAwareInterface 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
interface ExpressionLanguageAwareInterface
{
    /**
     * setExpressionLanguage 
     * 
     * @param ExpressionLanguage $expressionLanguage 
     * @return void
     */
    public function setExpressionLanguage(ExpressionLanguage $expressionLanguage);

    /**
     * getExpressionLanguage 
     * 
     * @return ExpressionLanguage 
     */
    public function getExpressionLanguage();
}
