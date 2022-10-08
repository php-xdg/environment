<?php declare(strict_types=1);

namespace Xdg\Environment\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\Exception\UnexpectedValueException;
use Xdg\Environment\SuperGlobalsProvider;

final class SuperGlobalsProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     * @backupGlobals enabled
     */
    public function testGetValue(array $server, array $env, string $key, ?string $expected): void
    {
        $provider = new SuperGlobalsProvider();
        $this->populateEnv($server, $env);
        Assert::assertSame($expected, $provider->get($key));
    }

    public function getValueProvider(): iterable
    {
        yield 'non-existent key' => [
            [], [], 'foo', null,
        ];
        yield 'prioritizes $_SERVER' => [
            ['foo' => 'bar'], ['foo' => 'baz'], 'foo', 'bar',
        ];
        yield '[internal] ensure env is cleaned up' => [
            [], [], 'foo', null,
        ];
        yield 'falls back to $_ENV' => [
            [], ['foo' => 'bar'], 'foo', 'bar',
        ];
        yield 'coerces "" to null' => [
            [], ['foo' => ''], 'foo', null,
        ];
        yield 'coerces false to null' => [
            [], ['foo' => false], 'foo', null,
        ];
    }

    /**
     * @dataProvider getFailsForNonScalarsProvider
     * @backupGlobals enabled
     */
    public function testGetFailsForNonScalars(array $server, array $env, string $key): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->populateEnv($server, $env);
        $provider = new SuperGlobalsProvider();
        $provider->get($key);
    }

    public function getFailsForNonScalarsProvider(): iterable
    {
        yield 'array' => [
            ['foo' => []], [],
            'foo',
        ];
        yield 'object' => [
            [], ['foo' => new \stdClass()],
            'foo',
        ];
    }

    /**
     * @backupGlobals enabled
     */
    public function testSetValue(): void
    {
        $env = new SuperGlobalsProvider();
        $key = uniqid(__METHOD__);
        $value = 'success!';
        $env->set($key, $value);
        Assert::assertSame($value, $_SERVER[$key] ?? null);
        Assert::assertSame($value, $_ENV[$key] ?? null);
    }

    /**
     * @backupGlobals enabled
     */
    public function testUnsetValue(): void
    {
        $key = uniqid(__METHOD__);
        $_SERVER[$key] = $_ENV[$key] = 'success!';

        $env = new SuperGlobalsProvider();
        $env->unset($key);
        Assert::assertNull($_SERVER[$key] ?? null);
        Assert::assertNull($_ENV[$key] ?? null);
    }

    private function populateEnv(array $server, array $env): void
    {
        foreach ($server as $key => $value) {
            $_SERVER[$key] = $value;
        }
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
        }
    }
}
