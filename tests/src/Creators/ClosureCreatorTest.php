<?php

namespace FitdevPro\DI\Tests\Creators;

use FitdevPro\DI\Creators\ClosureCreator;
use FitdevPro\DI\Options\Values\Value;
use FitdevPro\DI\TestsLib\FitTest;
use FitdevPro\DI\TestsLib\Stubs\MyService;

class ClosureCreatorTest extends FitTest
{
    public function badServiceDeclarationProvider()
    {
        return array([1], [true], [null], [new MyService()], ['sdsdsd']);
    }

    /**
     * Ustawianie błędnych danych serwisu
     *
     * @dataProvider badServiceDeclarationProvider
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 215
     */
    public function testBadServiceDeclaration($serviceDeclaration)
    {
        $creator = new ClosureCreator('test', $serviceDeclaration);

        $creator->get([]);
    }


    public function testGetClosureArgInline(){
        $creator = new ClosureCreator('test', function ($arg1){
            return $arg1;
        });

        $out = $creator->get([123]);

        $this->assertEquals(123, $out);
    }

    public function testGetClosureAgrsDefined(){
        $creator = new ClosureCreator(
            'test',
            function ($arg1) { return $arg1; },
            [ 'arguments'=>[ new Value(123), ] ]
        );

        $out = $creator->get([]);

        $this->assertEquals(123, $out);
    }

    public function testGetClosureAgrs(){
        $creator = new ClosureCreator(
            'test',
            function ($arg1, $arg2) { return $arg1.$arg2; },
            [ 'arguments'=>[ new Value('abc'), ] ]
        );

        $out = $creator->get([123]);

        $this->assertEquals('abc123', $out);
    }
}
