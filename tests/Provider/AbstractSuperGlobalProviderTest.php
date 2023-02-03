<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Exception\NonScalarValueException;

abstract class AbstractSuperGlobalProviderTest extends TestCase
{
    abstract protected static function createProvider(array $env): EnvironmentProviderInterface;
    abstract protected static function fetchEnv(string $key): mixed;

    /**
     * @dataProvider getValueProvider
     * @backupGlobals enabled
     */
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

    /**
     * @dataProvider getFailsForNonScalarsProvider
     * @backupGlobals enabled
     */
    public function testGetFailsForNonScalars(array $env, string $key): void
    {
        $this->expectException(NonScalarValueException::class);
        $provider = static::createProvider($env);
        $provider->get($key);
    }

    public function getFailsForNonScalarsProvider(): iterable
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

    /**
     * @backupGlobals enabled
     */
    public function testSetValue(): void
    {
        $provider = static::createProvider([]);
        $key = uniqid(__METHOD__);
        $value = 'success!';
        $provider->set($key, $value);
        Assert::assertSame($value, static::fetchEnv($key));
    }

    /**
     * @backupGlobals enabled
     */
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
