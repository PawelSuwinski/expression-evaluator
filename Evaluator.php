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

    /**
     * Invoke arguments context variables names. Default: arg0, arg1, etc.
     */
    protected $argNames;

    public function __construct($expression, array $context = [], array $argNames = [])
    {
        is_array($expression)
            ? $this->setExpressions($expression)
            : $this->addExpression($expression);

        $this->context = $context;

        foreach ($argNames as $name) {
            if (!is_string($name)) {
                throw new \UnexpectedValueException('String expected as argument name!');
            }
        }
        $this->argNames = $argNames;
    }

    public function __invoke()
    {
        foreach (func_get_args() as $id => $arg) {
            $this->context[$this->argNames[$id] ?? 'arg'.$id] = $arg;
        }
        $results = $this->evaluateExpressions($this->context);

        return end($results);
    }

    public function __call(string $name, array $arguments)
    {
        $this->context['method'] = $name;
        $this->context['arguments'] = $arguments;

        return $this(...$arguments);
    }

    public function catchExceptions()
    {
        $this->catchExceptions = true;
    }

    public function errorToException()
    {
        $this->errorToException = true;
    }
}
