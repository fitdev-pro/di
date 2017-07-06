<?php

namespace FitdevPro\DI\Creators;

/**
 * Interface IServiceCreator
 * @package FitdevPro\DI\Creators
 */
interface IServiceCreator
{
    public function get(array $params);
}
