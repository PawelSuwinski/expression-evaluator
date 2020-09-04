<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2016 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Expression;

use Symfony\Component\ExpressionLanguage\Expression;

/**
 * ExpressionEvaluatingTrait 
 * 
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
trait ExpressionEvaluatingTrait
{
    protected $expressions = array();

    /**
     * setExpressions 
     * 
     * @param array $expressions 
     * @return void
     */
    public function setExpressions(array $expressions)
    {
        $this->expressions = array();
        foreach($expressions as $expression) {
            $this->addExpression($expression);
        }
    }

    /**
     * addExpression 
     * 
     * @param string|Expression $expression 
     * @return void
     */
    public function addExpression($expression)
    {
        ExpressionValidator::validate($expression);
        $this->expressions[] = $expression;
    }

    /**
    * evaluateExpressions 
    * 
    * @param array $context 
    * @return array
    */
    protected function evaluateExpressions(array $context)
    {
        if(!$this instanceof ExpressionLanguageAwareInterface) {
            throw new \InvalidArgumentException(
                'ExpressionLanguageAwareInterface implementation required!'
            );
        }
        $results = array();
        foreach($this->expressions as $expression) {
            $context['result'] = empty($results) ? null : end($results);
            $results[] = $this->getExpressionLanguage()->evaluate(
                $expression, 
                $context
            );
        }
        return $results;
   }
}
