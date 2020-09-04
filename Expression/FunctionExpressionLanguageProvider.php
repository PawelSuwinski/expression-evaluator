<?php

/**
 * This file is part of the PsuwCommonListener package.
 *
 * @copyright Copyright (c) 2019 Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl>
 * @license MIT
 */

namespace Psuw\CommonListener\Expression;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;


/**
 * FunctionExpressionLanguageProvider
 *
 * @see Psuw\CommonListener\Tests\Expression\FunctionExpressionLanguageProviderTest For usage example
 * @package PsuwCommonListener
 * @copyright Copyright (c) 2019, Paweł Suwiński
 * @author Paweł Suwiński <psuw@wp.pl> 
 * @license MIT
 */
class FunctionExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    /**
     * config 
     * 
     * @var array
     */
    protected $config;

    /**
     * __construct 
     * 
     * @param array $config 
     * @return void
     */
    public function __construct(array $config) 
    {
        $this->config = $config;
    }


    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        $functions = array();
        foreach($this->config as $alias => $name) {
            $function = new \ReflectionFunction($name);
            $functions[] = new ExpressionFunction(
                is_string($alias) ? $alias : $name,
                /**
                 * Closure([mixed $...] ) : mixed 
                 */
                function() use ($function) {
                    return call_user_func_array(
                        'sprintf',
                        array_merge(
                            // %s(%s, %s...)
                            array(
                                '%s('.implode(
                                    ', ', 
                                    array_fill(0, func_num_args(), '%s')
                                ).')',
                                $function->getName(),
                            ),
                            func_get_args()
                        )
                    );
                },
                /**
                 * Closure(array $values[, mixed $...] ) : mixed 
                 */
                function() use ($function) {
                    $args = func_get_args();
                    array_shift($args);
                    return $function->invokeArgs($args);
                }
            );
        }
        return $functions;
    }
}

