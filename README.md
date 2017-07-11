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
use FitdevPro\DI\DependencyContainer;

$dc = new DependencyContainer(new CreatorFactory());

//add service
$dc->add('config', new stdClass());

//check if service exists
if($dc->has('config'))
{
    //get service
    $config = $dc->get('config');
    //OR
    $config = $dc->getConfig();
}

```

Create complicated services
```php
<?php
    
use FitdevPro\DI\Creators\CreatorFactory;
use FitdevPro\DI\DependencyContainer;
use FitdevPro\DI\Options\Actions\CallMethod;
use FitdevPro\DI\Options\Actions\SetProperty;
use FitdevPro\DI\Options\Values\ClassValue;
use FitdevPro\DI\Options\Values\ServiceValue;
use FitdevPro\DI\Options\Values\Value;

$dc = new DependencyContainer(new CreatorFactory());

//add service
$dc->add('bar', \MyFoo\Bar::class,
[
    "arguments" => [ // inject to constructor
        new Value(123), //simply value
        new ServiceValue($dc, 'serviceName'), //other service
        new ClassValue('Foo\Bar\Bazz'), //new object of some class
    ],
    "properties" => [ // inject to property
        new SetProperty('foo', new Value(123)),
        new SetProperty('bar', new ServiceValue($dc, 'serviceName')),
        new SetProperty('bazz', new ClassValue('Foo\Bar\Bazz')),
    ],
    "calls" => [ // call service method with arguments
        new CallMethod('setFoo', [new ServiceValue($dc, 'serviceName'), new Value('abc')]),
    ],
]
);

```

## Contribute

Please feel free to fork and extend existing or add new plugins and send a pull request with your changes!
To establish a consistent code quality, please provide unit tests for all your changes and may adapt the documentation.

## License

The MIT License (MIT). Please see [License File](https://github.com/fitdev-pro/di/blob/master/LISENCE) for more information.
