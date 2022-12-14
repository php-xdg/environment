<?php declare(strict_types=1);

namespace Xdg\Environment\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\ArrayProvider;
use Xdg\Environment\Exception\UnexpectedValueException;

final class ArrayProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue(array $env, string $key, ?string $expected): void
    {
        $provider = new ArrayProvider($env);
        Assert::assertSame($expected, $provider->get($key));
    }

    public function getValueProvider(): iterable
    {
        yield 'returns the value from the array' => [
            ['foo' => 'bar'],
            'foo',
            'bar',
        ];
        yield 'coerces false to null' => [
            ['foo' => false],
            'foo',
            null,
        ];
        yield 'coerces "" to null' => [
            ['foo' => ''],
            'foo',
            null,
        ];
    }

    /**
     * @dataProvider getFailsForNonScalarsProvider
     */
    public function testGetFailsForNonScalars(array $env, string $key): void
    {
        $this->expectException(UnexpectedValueException::class);
        $provider = new ArrayProvider($env);
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
     * @dataProvider setValueProvider
     */
    public function testSetValue(array $env, string $key, string $value, ?string $expected): void
    {
        $provider = new ArrayProvider($env);
        $provider->set($key, $value);
        Assert::assertSame($expected, $provider->get($key));
    }

    public function setValueProvider(): iterable
    {
        yield [
            [], 'foo', 'bar', 'bar',
        ];
        yield [
            ['foo' => 'nop'], 'foo', 'bar', 'bar',
        ];
    }

    /**
     * @dataProvider unsetValueProvider
     */
    public function testUnsetValue(array $env, string $key): void
    {
        $provider = new ArrayProvider($env);
        $provider->unset($key);
        Assert::assertNull($provider->get($key));
    }

    public function unsetValueProvider(): iterable
    {
        yield 'unset key' => [
            ['foo' => 'bar'], 'foo',
        ];
        yield 'unset missing key' => [
            ['foo' => 'bar'], 'baz',
        ];
    }
}
