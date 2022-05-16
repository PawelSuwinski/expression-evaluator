<?php

/**
 * This file is part of the PsuwExpressionEvaluator package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @license MIT
 */

namespace Psuw\ExpressionEvaluator;

use Psuw\ExpressionEvaluator\Expression\ExpressionLanguageAwareInterface;

/**
 * Evaluator.
 *
 * Return value of last expression.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
class Evaluator implements ExpressionLanguageAwareInterface
{
    use \Psuw\ExpressionEvaluator\Expression\ExpressionLanguageAwareTrait;
    use \Psuw\ExpressionEvaluator\Expression\ExpressionEvaluatingTrait;

    protected $context;

    public function __construct($expression, array $context = [], bool $catchExceptions = false)
    {
        $this->context = $context;
        $this->catchExceptions = $catchExceptions;
        is_array($expression)
            ? $this->setExpressions($expression)
            : $this->addExpression($expression);
    }

    public function __invoke()
    {
        foreach (func_get_args() as $id => $arg) {
            $this->context['arg'.$id] = $arg;
        }
        $results = $this->evaluateExpressions($this->context);

        return end($results);
    }
}
