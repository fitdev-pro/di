<?php

namespace FitdevPro\DI\Tests\Creators;

use FitdevPro\DI\Creators\ClassCreator;
use FitdevPro\DI\Creators\ObjectCreator;
use FitdevPro\DI\TestsLib\FitTest;
use FitdevPro\DI\TestsLib\Stubs\MyService;

class ClassCreatorTest extends FitTest
{
    public function badServiceDeclarationProvider()
    {
        return array([1], [true], [null], [new MyService()]);
    }

    /**
     * Ustawianie błędnych danych serwisu
     *
     * @dataProvider badServiceDeclarationProvider
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 16
     */
    public function testBadServiceDeclaration($serviceDeclaration)
    {
        $creator = new ClassCreator('test', $serviceDeclaration);

        $creator->get([]);
    }

    /**
     * Ustawianie błędnych danych serwisu
     *
     * @dataProvider badServiceDeclarationProvider
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 105
     */
    public function testClassNotExist()
    {
        $creator = new ClassCreator('test', 'Foo/Bar');

        $creator->get([]);
    }


    public function testGetClassObject(){
        $creator = new ClassCreator('test', MyService::class);

        $out = $creator->get([123]);

        $this->assertInstanceOf(MyService::class, $out);
        $this->assertEquals(123, $out->arg1);
    }
}
