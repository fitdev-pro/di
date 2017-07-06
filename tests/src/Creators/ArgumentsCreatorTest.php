<?php

namespace FitdevPro\DI\Tests\Creators;

use FitdevPro\DI\Creators\ClassCreator;
use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\DI\DependencyContainer;
use FitdevPro\DI\Options\Values\ClassValue;
use FitdevPro\DI\Options\Values\ServiceValue;
use FitdevPro\DI\Options\Values\Value;
use FitdevPro\DI\TestsLib\FitTest;
use FitdevPro\DI\TestsLib\Stubs\MyService;

class ArgumentsCreatorTest extends FitTest
{
    /**
     * Ustawianie błędnych danych serwisu
     *
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 28
     */
    public function testBadArguments(){
        new ClassCreator('test', MyService::class,
            [
                'arguments'=>[
                    'abc',
                ]
            ]
        );
    }

    public function testArgumentsValue(){
        $creator = new ClassCreator('test', MyService::class,
            [
                'arguments'=>[
                    new Value(123),
                ]
            ]
        );

        $out = $creator->get(['abc']);

        $this->assertEquals(123, $out->arg1);
        $this->assertEquals('abc', $out->arg2);
    }

    public function testArgumentsClassValue(){
        $creator = new ClassCreator('test', MyService::class,
            [
                'arguments'=>[
                    new ClassValue(\stdClass::class),
                    new ClassValue(\ArrayObject::class),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertInstanceOf(\stdClass::class, $out->arg1);
        $this->assertInstanceOf(\ArrayObject::class, $out->arg2);
    }

    public function testArgumentsServiceValue(){
        $dc = new DependencyContainer( new CreatorFactory() );
        $dc->add('myService1', new \stdClass());
        $dc->add('myService2', new \ArrayObject());

        $creator = new ClassCreator('test', MyService::class,
            [
                'arguments'=>[
                    new ServiceValue($dc, 'myService1'),
                    new ServiceValue($dc, 'myService2'),
                ]
            ]
        );

        $out = $creator->get([]);

        $this->assertInstanceOf(\stdClass::class, $out->arg1);
        $this->assertInstanceOf(\ArrayObject::class, $out->arg2);
    }
}
