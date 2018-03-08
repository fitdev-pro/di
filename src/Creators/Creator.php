<?php

namespace FitdevPro\DI\Creators;

use Assert\Assertion;
use FitdevPro\DI\Options\Actions\CallMethod;
use FitdevPro\DI\Options\Actions\SetProperty;
use FitdevPro\DI\Options\Values\IValue;

/**
 * Class Creator
 * @package FitdevPro\DI\Creators
 */
abstract class Creator implements IServiceCreator
{
    protected $name;
    protected $source;

    protected $shared = true;
    /** @var IValue[] */
    protected $arguments = array();
    /** @var SetProperty[] */
    protected $properties = array();
    /** @var CallMethod[] */
    protected $calls = array();

    protected $outputObject;

    /**
     * Service constructor.
     * @param string $name
     * @param $source
     * @param array $options
     * @param bool $shared
     */
    public function __construct(string $name, $source, array $options = array(), bool $shared = true)
    {
        $this->name = $name;
        $this->source = $source;

        $this->shared = $shared;

        if (isset($options['arguments'])) {
            Assertion::allIsInstanceOf($options['arguments'], IValue::class);

            $this->arguments = $options['arguments'];
        }

        if (isset($options['properties'])) {
            Assertion::allIsInstanceOf($options['properties'], SetProperty::class);

            $this->properties = $options['properties'];
        }

        if (isset($options['calls'])) {
            Assertion::allIsInstanceOf($options['calls'], CallMethod::class);

            $this->calls = $options['calls'];
        }
    }

    protected abstract function getObject(array $inLineArgs);

    final public function get(array $params)
    {
        if (is_null($this->outputObject) or !$this->shared) {
            $this->outputObject = $this->getObject($params);
            $this->setServiceProps();
            $this->callServiceMethod();
        }

        return $this->outputObject;
    }

    private function setServiceProps()
    {
        foreach ($this->properties as $property) {
            $property->execute($this->outputObject);
        }
    }

    private function callServiceMethod()
    {
        foreach ($this->calls as $method) {
            $method->execute($this->outputObject);
        }
    }
}
