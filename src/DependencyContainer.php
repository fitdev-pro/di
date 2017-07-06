<?php

namespace FitdevPro\DI;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use Closure;
use FitdevPro\DI\Creators\ICreatorFactory;
use FitdevPro\DI\Creators\IServiceCreator;
use FitdevPro\DI\Exception\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class DependencyContainer
 * @package FitdevPro\DI
 */
class DependencyContainer implements ContainerInterface
{
    const   EXCEPTION_PREFIX = '490430',
        SERVICE_NOT_FOUND = self::EXCEPTION_PREFIX . '001';

    /** @var IServiceCreator[] */
    protected $services = [];
    /** @var  ICreatorFactory */
    protected $creatorFactory;

    /**
     * DependencyContainer constructor.
     * @param ICreatorFactory $creatorFactory
     */
    public function __construct(ICreatorFactory $creatorFactory)
    {
        $this->creatorFactory = $creatorFactory;
    }


    /**
     * Ustawienie Servisu
     *
     * @param $name string - nazwa serwisu
     * @param Closure|string $source - funkcja która tworzy serwis i go zwraca lub nazwa klasy ktora ma byc stworzona
     * @param array $options
     *      [
     *          "arguments" => [ // inject to constructor
     *              new Options\Value($value),
     *              new Options\ServiceValue('serviceName'),
     *              new Options\ClassValue('Foo\Bar\Bazz'),
     *          ],
     *          "properties" => [ // inject to property
     *              new Options\Property('foo', new Options\Value($value)),
     *              new Options\Property('bar', new Options\ServiceValue('serviceName')),
     *              new Options\Property('bazz', new Options\ClassValue('Foo\Bar\Bazz')),
     *          ],
     *          "calls" => [ // call service method with arguments
     *              new Options\CallMethod('setFoo', [new Options\ServiceValue('serviceName'), new Options\Value($value)]),
     *          ],
     *      ]
     * @param $shared bool - czy serwis ma być dostępny jako jeden obiekt dla całej aplikacji
     * @throws \Exception
     */
    public function add(string $name, $source, array $options = [], bool $shared = true)
    {
        $this->services[$name] = $this->creatorFactory->create($name, $source, $options, $shared);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($name)
    {
        if (isset($this->services[$name])) {
            /** @var IServiceCreator $service */
            $service = $this->services[$name];

            //parametry wywolania metody
            $params = func_get_args();

            //usuniecie nazwy servisu z listy parametrow metody
            array_shift($params);

            return $service->get($params);
        }

        throw new NotFoundException('Nie ma takiego serwisu: ' . $name, self::SERVICE_NOT_FOUND);
    }


    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \ErrorException
     */
    public function __call($name, $arguments)
    {
        try {
            Assertion::startsWith($name, 'get');

            $serviceName = lcfirst(substr($name, 3));

            array_unshift($arguments, $serviceName);
            $out = call_user_func_array([self::class, 'get'], $arguments);

            return $out;

        } catch (InvalidArgumentException $e) {
            throw new \ErrorException('Call to undefined method: ' . self::class . ':' . $name . '()', E_USER_ERROR);
        }
    }
}
