<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Provider\EnvSuperGlobalProvider;

final class EnvSuperGlobalProviderTest extends SuperGlobalProviderTestCase
{
    protected static function createProvider(array $env): EnvironmentProviderInterface
    {
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
        }

        return new EnvSuperGlobalProvider();
    }

    protected static function fetchEnv(string $key): mixed
    {
        return $_ENV[$key] ?? null;
    }
}
