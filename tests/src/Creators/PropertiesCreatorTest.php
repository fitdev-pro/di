<?php

namespace FitdevPro\DI\Tests\Creators;

use FitdevPro\DI\Creators\ClassCreator;
use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\DI\DependencyContainer;
use FitdevPro\DI\Options\Actions\SetProperty;
use FitdevPro\DI\Options\Values\ClassValue;
use FitdevPro\DI\Options\Values\ServiceValue;
use FitdevPro\DI\Options\Values\Value;
use FitdevPro\DI\TestsLib\FitTest;
use FitdevPro\DI\TestsLib\Stubs\MyService;

class PropertiesCreatorTest extends FitTest
{

    public function testPropertiesValue(){
        $creator = new ClassCreator('test', MyService::class,
            [
                'properties'=>[
                    new SetProperty('param1', new Value(123)),
                    new SetProperty('param2', new Value('abc')),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertEquals(123, $out->param1);
        $this->assertEquals('abc', $out->param2);
    }

    public function testPropertiesClassValue(){
        $creator = new ClassCreator('test', MyService::class,
            [
                'properties'=>[
                    new SetProperty('param1', new ClassValue(\stdClass::class)),
                    new SetProperty('param2', new ClassValue(\ArrayObject::class)),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertInstanceOf(\stdClass::class, $out->param1);
        $this->assertInstanceOf(\ArrayObject::class, $out->param2);
    }

    public function testPropertiesServiceValue(){
        $dc = new DependencyContainer( new CreatorFactory() );
        $dc->add('myService1', new \stdClass());
        $dc->add('myService2', new \ArrayObject());

        $creator = new ClassCreator('test', MyService::class,
            [
                'properties'=>[
                    new SetProperty('param1', new ServiceValue($dc, 'myService1')),
                    new SetProperty('param2', new ServiceValue($dc, 'myService2')),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertInstanceOf(\stdClass::class, $out->param1);
        $this->assertInstanceOf(\ArrayObject::class, $out->param2);
    }
}
