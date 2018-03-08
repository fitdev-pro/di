<?php

namespace FitdevPro\DI\Creators;

use Assert\Assertion;

/**
 * Class ClosureCreator
 * @package FitdevPro\DI\Creators
 */
class ClosureCreator extends Creator
{
    /**
     * ServiceClosure constructor.
     * @param string $name
     * @param $source
     * @param array $options
     * @param bool $shared
     */
    public function __construct(string $name, $source, array $options = array(), bool $shared = true)
    {
        parent::__construct($name, $source, $options, $shared);

        Assertion::isCallable($this->source);
    }

    /**
     * @param array $inLineArgs
     * @return mixed
     */
    protected function getObject(array $inLineArgs)
    {
        $constructorArgs = $this->getClosureArgs($inLineArgs);

        return call_user_func_array($this->source, $constructorArgs);
    }

    private function getClosureArgs(array $inLineArgs)
    {
        $constructorArgs = array();

        foreach ($this->arguments as $argument) {
            $constructorArgs[] = $argument->get();
        }

        foreach ($inLineArgs as $param) {
            $constructorArgs[] = $param;
        }

        return $constructorArgs;
    }
}
