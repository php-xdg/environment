<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\BackupGlobals;
use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Provider\ServerSuperGlobalProvider;

final class ServerSuperGlobalProviderTest extends SuperGlobalProviderTestCase
{
    protected static function createProvider(array $env, ...$args): EnvironmentProviderInterface
    {
        foreach ($env as $key => $value) {
            $_SERVER[$key] = $value;
        }

        return new ServerSuperGlobalProvider(...$args);
    }

    protected static function fetchEnv(string $key): mixed
    {
        return $_SERVER[$key] ?? null;
    }
}
