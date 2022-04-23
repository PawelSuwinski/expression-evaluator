<?php

/**
 * This file is part of the PsuwExpressionEvaluator package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @license MIT
 */

namespace Psuw\ExpressionEvaluator\Expression;

use Symfony\Component\ExpressionLanguage\Expression;

/**
 * ExpressionEvaluatingTrait.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
trait ExpressionEvaluatingTrait
{
    protected $expressions = [];

    /**
     * setExpressions.
     */
    public function setExpressions(array $expressions)
    {
        $this->expressions = [];
        foreach ($expressions as $expression) {
            $this->addExpression($expression);
        }
    }

    /**
     * addExpression.
     *
     * @param string|Expression $expression
     */
    public function addExpression($expression)
    {
        ExpressionValidator::validate($expression);
        $this->expressions[] = $expression;
    }

    /**
     * evaluateExpressions.
     *
     * @return array
     */
    protected function evaluateExpressions(array $context)
    {
        if (!$this instanceof ExpressionLanguageAwareInterface) {
            throw new \InvalidArgumentException('ExpressionLanguageAwareInterface implementation required!');
        }
        $results = [];
        foreach ($this->expressions as $expression) {
            $context['result'] = empty($results) ? null : end($results);
            $results[] = $this->getExpressionLanguage()->evaluate($expression, $context);
        }

        return $results;
    }
}
