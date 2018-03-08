<?php

namespace FitdevPro\DI\Creators;

use Assert\Assertion;

/**
 * Class ObjectCreator
 * @package FitdevPro\DI\Creators
 */
class ObjectCreator extends Creator
{
    /**
     * ServiceObject constructor.
     * @param string $name
     * @param $source
     * @param array $options
     * @param bool $shared
     */
    public function __construct(string $name, $source, array $options = array(), bool $shared = true)
    {
        parent::__construct($name, $source, $options, $shared);

        Assertion::isObject($source);
    }

    /**
     * @param array $inLineArgs
     * @return mixed
     */
    protected function getObject(array $inLineArgs)
    {
        return $this->source;
    }
}
