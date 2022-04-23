<?php

/**
 * This file is part of the PsuwExpressionEvaluator package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @license MIT
 */

namespace Psuw\ExpressionEvaluator\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * ExpressionLanguageAwareTrait.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
trait ExpressionLanguageAwareTrait
{
    protected $expressionLanguage;

    /**
     * setExpressionLanguage.
     */
    public function setExpressionLanguage(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * getExpressionLanguage.
     *
     * @return ExpressionLanguage
     */
    public function getExpressionLanguage()
    {
        if (!isset($this->expressionLanguage)) {
            $this->expressionLanguage = new ExpressionLanguage();
        }

        return $this->expressionLanguage;
    }
}
