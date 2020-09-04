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
 * ExpressionLanguageAwareTrait 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
trait ExpressionLanguageAwareTrait
{
    protected $expressionLanguage;

    /**
     * setExpressionLanguage 
     * 
     * @param ExpressionLanguage $expressionLanguage 
     * @return void
     */
    public function setExpressionLanguage(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * getExpressionLanguage 
     * 
     * @return ExpressionLanguage 
     */
    public function getExpressionLanguage()
    {
        if(!isset($this->expressionLanguage)) {
            $this->expressionLanguage = new ExpressionLanguage();
        }
        return $this->expressionLanguage;
    }
}
