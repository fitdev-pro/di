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
     * @param string $serviceName
     */
    public function __construct(DependencyContainer $di, string $serviceName)
    {
        parent::__construct($serviceName);
        $this->di = $di;
    }

    public function get()
    {
        return $this->di->get($this->value);
    }
}
