<?php

namespace FitdevPro\DI\Tests\src;

use FitdevPro\DI\DependencyContainer;
use FitdevPro\DI\Exception\ContainerException;
use FitdevPro\DI\Service;
use FitdevPro\DI\Tests\Libs\FitTest;
use FitdevPro\DI\Tests\Libs\Stubs\MyService;

/**
 * Class ServiceTest
 * @package FitdevPro\DI\Tests\src
 */
class ServiceTest extends FitTest
{
    protected $myServiceArgs = [['type' => 'parameter', 'value' => 1,], ['type' => 'parameter', 'value' => 2,],];

    public function badClassNameProvider()
    {
        return array([1], [true], [null], [[]]);
    }

    /**
     * Ustawianie błędnych danych serwisu
     *
     * @dataProvider badClassNameProvider
     * @expectedException ContainerException
     * @expectedExceptionCode 491901
     */
    public function testCreateServiceException($badClassName)
    {
        new Service(new DependencyContainer(), 'myService', $badClassName);
    }

    //-----------------------------------------------------------------------------

    public function testSetServiceShared()
    {
        $service = new Service(new DependencyContainer(), 'myService', \stdClass::class, ['shared' => true]);

        $obj = $service->getObject([]);
        $obj->test = 1;

        $obj2 = $service->getObject([]);
        $this->assertObjectHasAttribute('test', $obj2);
        $this->assertEquals(1, $obj2->test);

        $service = new Service(new DependencyContainer(), 'myService', \stdClass::class);

        $objN = $service->getObject([]);
        $objN->test = 1;

        $objN2 = $service->getObject([]);

        $this->assertObjectNotHasAttribute('test', $objN2);
    }

    //-----------------------------------------------------------------------------

    public function testCreateObjectFromClassName()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => $this->myServiceArgs]
        );

        /** @var MyService $obj */
        $obj = $service->getObject([]);
        $this->assertEquals(1, $obj->arg1);
        $this->assertEquals(2, $obj->arg2);

        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            [
                'arguments' => [
                    ['type' => 'class', 'name' => \stdClass::class,],
                ]
            ]
        );

        /** @var MyService $obj */
        $obj = $service->getObject([3]);
        $this->assertInstanceOf(\stdClass::class, $obj->arg1);
        $this->assertEquals(3, $obj->arg2);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 105
     */
    public function testCreateObjectFromClassNameException()
    {
        $service = new Service(new DependencyContainer(), 'myService', 'NoClassName');
        $service->getObject([]);
    }

    //-----------------------------------------------------------------------------

    public function testCreateObjectFromClosure()
    {
        $service = new Service(new DependencyContainer(), 'myService',
            function () {
                return new MyService(1, 2);
            }
        );

        /** @var MyService $obj */
        $obj = $service->getObject([]);
        $this->assertEquals(1, $obj->arg1);
        $this->assertEquals(2, $obj->arg2);

        $service = new Service(new DependencyContainer(), 'myService',
            function ($arg2) {
                return new MyService(1, $arg2);
            }
        );

        /** @var MyService $obj */
        $obj = $service->getObject([3]);
        $this->assertEquals(1, $obj->arg1);
        $this->assertEquals(3, $obj->arg2);
    }

    //-----------------------------------------------------------------------------

    public function testPopulateProperties()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            [
                'arguments' => $this->myServiceArgs,
                'properties' => [
                    ['name' => 'param1', 'value' => ['type' => 'parameter', 'value' => 'xxx'],],
                    ['name' => 'param2', 'value' => ['type' => 'parameter', 'value' => 'yyy'],],
                ]
            ]
        );

        /** @var MyService $obj */
        $obj = $service->getObject([]);
        $this->assertEquals('xxx', $obj->param1);
        $this->assertEquals('yyy', $obj->param2);
    }

    public function propertySettingsProvider()
    {
        return array(
            [['value' => ['type' => 'parameter', 'value' => 'xxx']]],
            [['name' => 'param1']],
        );
    }

    /**
     * @dataProvider propertySettingsProvider
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 26
     */
    public function testPopulatePropertiesExceptionPropertySettings($property)
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            [
                'arguments' => $this->myServiceArgs,
                'properties' => [$property,]
            ]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 224
     */
    public function testPopulatePropertiesExceptionPropertyNotExists()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            [
                'arguments' => $this->myServiceArgs,
                'properties' => [
                    ['name' => 'param99', 'value' => ['type' => 'parameter', 'value' => 'xxx']],
                ]
            ]
        );

        $service->getObject([]);
    }

    //-----------------------------------------------------------------------------

    public function testCallMethod()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            [
                'arguments' => $this->myServiceArgs,
                'calls' => [
                    ['name' => 'setMethod1',],
                    [
                        'name' => 'setMethod2',
                        'arguments' => [
                            ['type' => 'parameter', 'value' => 'aaa']
                        ],
                    ],
                ]
            ]
        );

        /** @var MyService $obj */
        $obj = $service->getObject([]);
        $this->assertEquals('call_method_1', $obj->method1);
        $this->assertEquals('aaa', $obj->method2);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 26
     */
    public function testCallMethodExceptionMethodSettings()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => $this->myServiceArgs, 'calls' => [['foo' => 'bar']]]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 208
     */
    public function testCallMethodExceptionMethodNotExists()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => $this->myServiceArgs, 'calls' => [['name' => 'noMethod'],]]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 26
     */
    public function testGetValueExceptionToType()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => [['value' => 1,]]]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 22
     */
    public function testGetValueExceptionBadType()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => [['type' => 'foo', 'value' => 1,]]]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 26
     */
    public function testGetValueExceptionNoNameClass()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => [['type' => 'class', 'value' => 1,]]]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 26
     */
    public function testGetValueExceptionNoValue()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => [['type' => 'parameter']]]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @expectedExceptionCode 26
     */
    public function testGetValueExceptionNoNameService()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => [['type' => 'service', 'value' => 1,]]]
        );

        $service->getObject([]);
    }

    /**
     * @expectedException \FitdevPro\DI\ContainerException
     * @expectedExceptionCode 494301
     */
    public function testGetValueExceptionNoService()
    {
        $service = new Service(new DependencyContainer(), 'myService', MyService::class,
            ['arguments' => [['type' => 'service', 'name' => 'foo']]]
        );

        $service->getObject([]);
    }
}
