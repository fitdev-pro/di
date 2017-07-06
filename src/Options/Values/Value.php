<?php

namespace FitdevPro\DI\Options\Values;

/**
 * Class Value
 * @package FitdevPro\DI\Options\Values
 */
class Value implements IValue
{
    protected $value;

    /**
     * Value constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }
}
