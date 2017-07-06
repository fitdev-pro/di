<?php

namespace FitdevPro\DI\TestsLib\Stubs;

/**
 * Class MyService
 * @package FitdevPro\DI\Tests\Libs\Stubs
 */
class MyService
{
    public $arg1;
    public $arg2;

    public $param1;
    public $param2;

    public $method1;
    public $method2;

    /**
     * MyService constructor.
     * @param $arg1
     * @param $arg2
     */
    public function __construct($arg1=null, $arg2=null)
    {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }


    public function setMethod1()
    {
        $this->method1 = 'call_method_1';
    }

    /**
     * @param mixed $method2
     */
    public function setMethod2($method2)
    {
        $this->method2 = $method2;
    }
}
