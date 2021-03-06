<?php

namespace FitdevPro\DI\Creators;

/**
 * Interface ICreatorFactory
 * @package FitdevPro\DI\Creators
 */
interface ICreatorFactory
{
    public function create(string $name, $source, array $options = array(), bool $shared = true): IServiceCreator;
}
