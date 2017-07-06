<?php

namespace FitdevPro\DI\Creators;

use Assert\Assertion;
use ReflectionClass;

/**
 * Class ClassCreator
 * @package FitdevPro\DI\Creators
 */
class ClassCreator extends Creator
{
    /**
     * ServiceClass constructor.
     * @param string $name
     * @param $source
     * @param array $options
     * @param bool $shared
     */
    public function __construct(string $name, $source, array $options = [], bool $shared = true)
    {
        parent::__construct($name, $source, $options, $shared);

        Assertion::string($source);
        Assertion::classExists($source);
    }


    /**
     * @param array $inLineArgs
     * @return object
     */
    protected function getObject(array $inLineArgs)
    {
        $constructorArgs = $this->getConstructArgs($inLineArgs);

        $reflectionClass = new ReflectionClass($this->source);
        $obj = $reflectionClass->newInstanceArgs($constructorArgs);

        if ($obj instanceof IServiceCreator) {
            return $obj->get([]);
        }else{
            return $obj;
        }

    }

    private function getConstructArgs(array $inLineArgs)
    {
        $constructorArgs = [];

        foreach ($this->arguments as $argument) {
            $constructorArgs[] = $argument->get();
        }

        foreach ($inLineArgs as $param) {
            $constructorArgs[] = $param;
        }

        return $constructorArgs;
    }
}
