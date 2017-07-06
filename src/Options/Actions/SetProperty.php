<?php

namespace FitdevPro\DI\Options\Actions;

use Assert\Assertion;
use FitdevPro\DI\Options\Values\IValue;

/**
 * Class SetProperty
 * @package FitdevPro\DI\Options\Acts
 */
class SetProperty implements IAction
{
    protected $name;
    protected $value;

    /**
     * Property constructor.
     * @param string $name
     * @param $value
     */
    public function __construct(string $name, IValue $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function execute($object)
    {
        Assertion::propertyExists($object, $this->name,
            'Not exists property "' . $this->name . '" specified to set in class: ' . get_class($object) . ' in service ' . $this->name . '.');

        $name = $this->name;
        $object->$name = $this->value->get();
    }
}
