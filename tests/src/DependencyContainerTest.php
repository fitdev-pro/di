<?php

namespace FitdevPro\DI\Tests;

use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\DI\DependencyContainer;
use FitdevPro\DI\TestsLib\FitTest;

/**
 * Class DependencyContainerTest
 * @package FitdevPro\DI
 */
class DependencyContainerTest extends FitTest
{
    public function badClassNameProvider()
    {
        return array([1], [true], [null], [[]]);
    }

    /**
     * Ustawianie błędnych danych serwisu
     *
     * @dataProvider badClassNameProvider
     * @expectedException \FitdevPro\DI\Exception\ContainerException
     * @expectedExceptionCode 490360001
     */
    public function testSetServiceException($badClassName)
    {
        $dc = new DependencyContainer(new CreatorFactory());
        $dc->add('service', $badClassName);
    }

    /**
     * Pobieranie serwisu
     */
    public function testGetService()
    {
        $dc = new DependencyContainer( new CreatorFactory() );
        $dc->add('myService', new \stdClass());

        $this->assertInstanceOf(\stdClass::class, $dc->get('myService'));
        $this->assertInstanceOf(\stdClass::class, $dc->getMyService());
    }

    /**
     * @expectedException \ErrorException
     */
    public function testGetServiceError()
    {
        $dc = new DependencyContainer( new CreatorFactory() );
        $dc->add('myService', new \stdClass());

        $this->assertInstanceOf(\stdClass::class, $dc->myService());
    }

    /**
     * Pobieranie nie istniejącego serwisu
     *
     * @expectedException \FitdevPro\DI\Exception\NotFoundException
     * @expectedExceptionCode 490430001
     */
    public function testGetServiceException()
    {
        $dc = new DependencyContainer( new CreatorFactory() );
        $dc->get('service');
    }

    /**
     * Pobieranie nie istniejącego serwisu
     *
     * @expectedException \FitdevPro\DI\Exception\NotFoundException
     * @expectedExceptionCode 490430001
     */
    public function testGetServiceCallException()
    {
        $dc = new DependencyContainer( new CreatorFactory() );
        $dc->getMyService();
    }
}
