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
 * ExpressionValidator.
 *
 * @copyright Copyright (c) 2016, Paweł Suwiński
 * @license MIT
 */
class ExpressionValidator
{
    /**
     * validate.
     *
     * @throw \InvalidArgumentException on invalid format
     *
     * @param mixed $expression
     */
    public static function validate($expression)
    {
        if (!self::isValid($expression)) {
            throw new \InvalidArgumentException(
                'string or "Symfony\Component\ExpressionLanguage\Expression" '.'instance expected as expression!'
            );
        }
    }

    /**
     * isValid.
     *
     * @param mixed $expression
     *
     * @return bool
     */
    public static function isValid($expression)
    {
        return is_string($expression) || $expression instanceof Expression;
    }
}
