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
    protected $catchExceptions = false;

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
        $context['exception'] = null;
        if ($this->catchExceptions) {
            set_error_handler(function ($severity, $message, $file, $line) {
                if (!(error_reporting() & $severity)) {
                    return;
                }
                throw new \ErrorException($message, 0, $severity, $file, $line);
            });
        }
        foreach ($this->expressions as $expression) {
            $context['result'] = empty($results) ? null : end($results);
            try {
                $results[] = $this->getExpressionLanguage()->evaluate($expression, $context);
                $context['exception'] = null;
            } catch (\Exception $e) {
                if (!$this->catchExceptions) {
                    throw $e;
                }
                $results[] = null;
                $context['exception'] = $e;
            }
        }
        if ($this->catchExceptions) {
            restore_error_handler();
        }

        return $results;
    }
}
