<?php

namespace FitdevPro\DI\Tests\Creators;

use FitdevPro\DI\Creators\ClassCreator;
use FitdevPro\DI\Creators\ClosureCreator;
use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\DI\Creators\ObjectCreator;
use FitdevPro\DI\TestsLib\Stubs\MyService;
use FitdevPro\DI\TestsLib\FitTest;

class CreatorFactoryTest extends FitTest
{
    public function badServiceDeclarationProvider()
    {
        return array([1], [true], [null], [[]]);
    }

    /**
     * Ustawianie błędnych danych serwisu
     *
     * @dataProvider badServiceDeclarationProvider
     * @expectedException \FitdevPro\DI\Exception\ContainerException
     */
    public function testBadServiceDeclaration($serviceDeclaration)
    {
        $dc = new CreatorFactory();
        $dc->create('service', $serviceDeclaration);
    }


    public function testClosureCreator(){
        $creator = new CreatorFactory();

        $out = $creator->create('test', function (){});

        $this->assertInstanceOf(ClosureCreator::class, $out);
    }

    public function testClassCreator(){
        $creator = new CreatorFactory();

        $out = $creator->create('test', MyService::class);

        $this->assertInstanceOf(ClassCreator::class, $out);
    }

    public function testObjectCreator(){
        $creator = new CreatorFactory();

        $out = $creator->create('test', new \stdClass());

        $this->assertInstanceOf(ObjectCreator::class, $out);
    }
}
