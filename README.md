# FitDI

PHP 7.0 Dependency Container Implementation.

## Installation

```
composer require fitdev-pro/di
```

## Usage

Base usage
```php
<?php
    
use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\FitApp\Interfaces\IDependencyContainer;

$di = new Di(new CreatorFactory());

//add service
$di->add('config', new stdClass());

//check if service exists
if($di->has('config'))
{
    //get service
    $config = $di->get('config');
    //OR
    $config = $di->getConfig();
}

```

Create complicated services
```php
<?php
    
use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\FitApp\Interfaces\IDependencyContainer;

$di = new Di(new CreatorFactory());

//add service
$di->add('bar', My\Foo\Bar::class,
[
    "arguments" => [ // inject to constructor
        new Options\Value($value), //simply value
        new Options\ServiceValue('serviceName'), //other service
        new Options\ClassValue('Foo\Bar\Bazz'), //new object of some class
    ],
    "properties" => [ // inject to property
        new Options\Property('foo', new Options\Value($value)),
        new Options\Property('bar', new Options\ServiceValue('serviceName')),
        new Options\Property('bazz', new Options\ClassValue('Foo\Bar\Bazz')),
    ],
    "calls" => [ // call service method with arguments
        new Options\CallMethod('setFoo', [new Options\ServiceValue('serviceName'), new Options\Value($value)]),
    ],
]
);

```

## Contribute

Please feel free to fork and extend existing or add new plugins and send a pull request with your changes!
To establish a consistent code quality, please provide unit tests for all your changes and may adapt the documentation.

## License

The MIT License (MIT). Please see [License File](https://github.com/italolelis/collections/blob/master/LICENSE) for more information.
