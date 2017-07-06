<?php

namespace FitdevPro\DI\Tests\Creators;

use FitdevPro\DI\Creators\ClassCreator;
use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\DI\DependencyContainer;
use FitdevPro\DI\Options\Actions\CallMethod;
use FitdevPro\DI\Options\Actions\SetProperty;
use FitdevPro\DI\Options\Values\ClassValue;
use FitdevPro\DI\Options\Values\ServiceValue;
use FitdevPro\DI\Options\Values\Value;
use FitdevPro\DI\TestsLib\FitTest;
use FitdevPro\DI\TestsLib\Stubs\MyService;

class CallMethosCreatorTest extends FitTest
{

    public function testPropertiesValue(){
        $creator = new ClassCreator('test', MyService::class,
            [
                'calls'=>[
                    new CallMethod('setMethod1'),
                    new CallMethod('setMethod2', [new Value('abc')]),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertEquals('call_method_1', $out->method1);
        $this->assertEquals('abc', $out->method2);
    }

    public function testPropertiesClassValue(){
        $creator = new ClassCreator('test', MyService::class,
            [
                'calls'=>[
                    new CallMethod('setMethod2', [new ClassValue(\ArrayObject::class)]),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertInstanceOf(\ArrayObject::class, $out->method2);
    }

    public function testPropertiesServiceValue(){
        $dc = new DependencyContainer( new CreatorFactory() );
        $dc->add('myService1', new \stdClass());
        $dc->add('myService2', new \ArrayObject());

        $creator = new ClassCreator('test', MyService::class,
            [
                'calls'=>[
                    new CallMethod('setMethod2', [new ServiceValue($dc, 'myService2')]),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertInstanceOf(\ArrayObject::class, $out->method2);
    }
}
