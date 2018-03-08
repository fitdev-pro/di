<?php

namespace FitdevPro\DI\Options\Values;

use FitdevPro\DI\DependencyContainer;

/**
 * Class ServiceValue
 * @package FitdevPro\DI\Options\Values
 */
class ServiceValue extends Value
{
    protected $di;

    /**
     * ValueService constructor.
     * @param DependencyContainer $di
     * @param string $serviceName
     */
    public function __construct(DependencyContainer $di, string $serviceName)
    {
        parent::__construct($serviceName);
        $this->di = $di;
    }

    /**
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function get()
    {
        return $this->di->get($this->value);
    }
}
