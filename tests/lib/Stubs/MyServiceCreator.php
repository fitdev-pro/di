<?php

namespace FitdevPro\DI\TestsLib\Stubs;

use FitdevPro\DI\Creators\IServiceCreator;

/**
 * Class MyServiceCreator
 * @package FitdevPro\DI\TestsLib\Stubs
 */
class MyServiceCreator implements IServiceCreator
{
    private $arg1;

    /**
     * MyServiceCreator constructor.
     * @param $args
     */
    public function __construct($arg1)
    {
        $this->arg1 = $arg1;
    }


    public function get(array $params)
    {
        return new MyService($this->arg1);
    }
}
