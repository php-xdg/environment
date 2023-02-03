# xdg/environment

[![codecov](https://codecov.io/gh/php-xdg/environment/branch/main/graph/badge.svg?token=QE672UK2ZG)](https://codecov.io/gh/php-xdg/environment)

An abstraction layer over environment variables.

## Installation

```sh
composer require xdg/environment
```

## Usage

The library consists of several classes implementing the `Xdg\Environment\EnvironmentProviderInterface`:

```php
use Xdg\Environment\XdgEnvironment;
use Xdg\Environment\Provider\ArrayProvider;
use Xdg\Environment\Provider\ChainProvider;
use Xdg\Environment\Provider\EnvSuperGlobalProvider;
use Xdg\Environment\Provider\GetenvProvider;
use Xdg\Environment\Provider\ServerSuperGlobalProvider;

// fetches values from the $_ENV super-global
$env = new EnvSuperGlobalProvider();
$value = $env->set('FOO', 'bar');
assert($env->get('FOO') === $value);
assert($_ENV['FOO'] === $value);

$env->unset('FOO');
assert($env->get('FOO') === null);
assert(!isset($_ENV['FOO']));

// fetches values from the $_SERVER super-global
$env = new ServerSuperGlobalProvider();

// fetches values using the getenv() function
$env = new GetenvProvider();

// fetches values using the provided array as a backing store (useful for testing)
$env = new ArrayProvider(['FOO' => 'bar']);

// fetches values from a chain of providers, returning the first non-null value found.
$env = new ChainProvider(
    new ServerSuperGlobalProvider(),
    new EnvSuperGlobalProvider(),
    new GetenvProvider(),
);
// as a shortcut for the former, you can use:
$env = XdgEnvironment::default();
// when updating a value, all providers in the chain are updated:
$env->set('FOO', 'bar');
assert($_SERVER['FOO'] === 'bar')
assert($_ENV['FOO'] === 'bar')
assert(getenv('FOO') === 'bar')
```
