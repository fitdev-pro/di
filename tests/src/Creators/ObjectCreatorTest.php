<?php

namespace FitdevPro\DI\Tests\Creators;

use FitdevPro\DI\Creators\ObjectCreator;
use FitdevPro\DI\TestsLib\FitTest;

class ObjectCreatorTest extends FitTest
{
    public function badServiceDeclarationProvider()
    {
        return array([1], [true], [null], [[]]);
    }

    /**
     * Ustawianie błędnych danych serwisu
     *
     * @dataProvider badServiceDeclarationProvider
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 207
     */
    public function testBadServiceDeclaration($serviceDeclaration)
    {
        $creator = new ObjectCreator('test', $serviceDeclaration);

        $creator->get([]);
    }


    public function testGetObject(){
        $creator = new ObjectCreator('test', new \stdClass());

        $out = $creator->get([]);

        $this->assertInstanceOf(\stdClass::class, $out);
    }
}
