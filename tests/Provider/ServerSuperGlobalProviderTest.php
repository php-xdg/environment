<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Provider\ServerSuperGlobalProvider;

final class ServerSuperGlobalProviderTest extends SuperGlobalProviderTestCase
{
    protected static function createProvider(array $env): EnvironmentProviderInterface
    {
        foreach ($env as $key => $value) {
            $_SERVER[$key] = $value;
        }

        return new ServerSuperGlobalProvider();
    }

    protected static function fetchEnv(string $key): mixed
    {
        return $_SERVER[$key] ?? null;
    }
}
