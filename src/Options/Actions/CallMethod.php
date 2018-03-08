<?php

namespace FitdevPro\DI\Options\Actions;

use Assert\Assertion;
use FitdevPro\DI\Options\Values\IValue;
use ReflectionClass;

/**
 * Class CallMethod
 * @package FitdevPro\DI\Options\Acts
 */
class CallMethod implements IAction
{
    protected $name;
    /** @var IValue[] */
    protected $args = array();

    /**
     * CallMethod constructor.
     * @param $name
     * @param array $args
     */
    public function __construct(string $name, array $args = array())
    {
        $this->name = $name;

        Assertion::allIsInstanceOf($args, IValue::class);
        $this->args = $args;
    }

    public function execute($object)
    {
        Assertion::methodExists($this->name, $object,
            'Not exists method "' . $this->name . '" specified to call in class: ' . get_class($object) . 'in service ' . $this->name . '.');

        $args = array();

        foreach ($this->args as $arg) {
            $args[] = $arg->get();
        }

        $reflectionClass = new ReflectionClass($object);
        $reflectionClass->getMethod($this->name)->invokeArgs($object, $args);
    }
}
