<?php

namespace FitdevPro\DI\Creators;

use Closure;
use FitdevPro\DI\Exception\ContainerException;

/**
 * Class CreatorFactory
 * @package FitdevPro\DI\Creators
 */
class CreatorFactory implements ICreatorFactory
{
    const   EXCEPTION_PREFIX = '490360',
        BAD_SERVICE_TYPE = self::EXCEPTION_PREFIX . '001';

    public function create(string $name, $source, array $options = [], bool $shared = true): IServiceCreator
    {
        switch (true) {
            case $source instanceof Closure:
                $service = new ClosureCreator($name, $source, $options, $shared);
                break;
            case is_object($source):
                $service = new ObjectCreator($name, $source, $options, $shared);
                break;
            case is_string($source):
                $service = new ClassCreator($name, $source, $options, $shared);
                break;
            default:
                throw new ContainerException('Nieobsługiwany sposób tworzenia serwisu.', self::BAD_SERVICE_TYPE);
        }

        return $service;
    }
}
