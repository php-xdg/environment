<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\BackupGlobals;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Exception\NonScalarValueException;

abstract class SuperGlobalProviderTestCase extends TestCase
{
    abstract protected static function createProvider(array $env): EnvironmentProviderInterface;
    abstract protected static function fetchEnv(string $key): mixed;

    #[DataProvider('getValueProvider')]
    #[BackupGlobals(true)]
    public function testGetValue(array $env, string $key, ?string $expected): void
    {
        $provider = static::createProvider($env);
        Assert::assertSame($expected, $provider->get($key));
    }

    public static function getValueProvider(): iterable
    {
        yield 'non-existent key' => [
            [], 'foo', null,
        ];
        yield '[internal] ensure env is cleaned up' => [
            [], 'foo', null,
        ];
        yield 'coerces "" to null' => [
            ['foo' => ''], 'foo', null,
        ];
        yield 'coerces false to null' => [
            ['foo' => false], 'foo', null,
        ];
    }

    #[DataProvider('getFailsForNonScalarsProvider')]
    #[BackupGlobals(true)]
    public function testGetFailsForNonScalars(array $env, string $key): void
    {
        $this->expectException(NonScalarValueException::class);
        $provider = static::createProvider($env);
        $provider->get($key);
    }

    public static function getFailsForNonScalarsProvider(): iterable
    {
        yield 'array' => [
            ['foo' => []],
            'foo',
        ];
        yield 'object' => [
            ['foo' => new \stdClass()],
            'foo',
        ];
    }

    #[BackupGlobals(true)]
    public function testSetValue(): void
    {
        $provider = static::createProvider([]);
        $key = uniqid(__METHOD__);
        $value = 'success!';
        $provider->set($key, $value);
        Assert::assertSame($value, static::fetchEnv($key));
    }

    #[BackupGlobals(true)]
    public function testUnsetValue(): void
    {
        $key = uniqid(__METHOD__);
        $provider = static::createProvider([
            $key => 'success!'
        ]);
        $provider->unset($key);
        Assert::assertNull(static::fetchEnv($key));
    }
}
