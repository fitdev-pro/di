<?php

namespace FitdevPro\DI\TestsLib\Stubs;

use FitdevPro\DI\Creators\IServiceCreator;

/**
 * Class MyServiceCreator
 * @package FitdevPro\DI\TestsLib\Stubs
 */
class MyServiceCreator implements IServiceCreator
{

    public function get(array $params)
    {
        return new MyService();
    }
}
