<?php

namespace FitdevPro\DI\Options\Values;

use ReflectionClass;

/**
 * Class ClassValue
 * @package FitdevPro\DI\Options\Values
 */
class ClassValue extends Value
{
    /**
     * ValueService constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    public function get()
    {
        $reflectionClass = new ReflectionClass($this->value);
        return $reflectionClass->newInstanceArgs();
    }
}
