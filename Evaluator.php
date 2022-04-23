<?php

/**
 * This file is part of the PsuwExpressionEvaluator package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @license MIT
 */

namespace Psuw\ExpressionEvaluator\HttpKernel\EventListener;

/**
 * Evaluator.
 *
 * Return value of last expression.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
class Evaluator
{
    use \Psuw\ExpressionEvaluator\Expression\ExpressionEvaluatingTrait
    {
        setExpressions as protected;
        addExpression as protected;
    }

    protected $context;

    public function __construct($expression, array $context = [])
    {
        $this->context = $context;
        is_array($expression)
            ? $this->setExpressions($expression)
            : $this->addExpression($expression);
    }

    public function __invoke()
    {
        foreach (func_get_args() as $id => $arg) {
            $this->context['arg'.$id] = $arg;
        }

        return end($this->evaluateExpressions($this->context));
    }
}
