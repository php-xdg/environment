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

    #[BackupGlobals(true)]
    public function testGetIgnoresHttpVariables(): void
    {
        $provider = self::createProvider([
            'HTTP_FOO' => 'foo',
        ]);
        Assert::assertNull($provider->get('HTTP_FOO'));
    }

    #[BackupGlobals(true)]
    public function testSetIgnoresHttpVariables(): void
    {
        $provider = self::createProvider([]);
        $provider->set('HTTP_FOO', 'bar');
        Assert::assertNull(self::fetchEnv('HTTP_FOO'));
    }

    #[BackupGlobals(true)]
    public function testUnsetIgnoresHttpVariables(): void
    {
        $provider = self::createProvider([
            'HTTP_FOO' => 'foo',
        ]);
        $provider->unset('HTTP_FOO');
        Assert::assertSame('foo', self::fetchEnv('HTTP_FOO'));
    }
}
