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
 * ExpressionLanguageAwareInterface.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
interface ExpressionLanguageAwareInterface
{
    /**
     * setExpressionLanguage.
     */
    public function setExpressionLanguage(ExpressionLanguage $expressionLanguage);

    /**
     * getExpressionLanguage.
     *
     * @return ExpressionLanguage
     */
    public function getExpressionLanguage();
}
