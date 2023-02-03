<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\Provider\GetenvProvider;

final class GetenvProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue(array $env, string $key, ?string $expected): void
    {
        $provider = new GetenvProvider();
        $cleanup = $this->populateEnv($env);
        try {
            Assert::assertSame($expected, $provider->get($key));
        } finally {
            $cleanup();
        }
    }

    public function getValueProvider(): iterable
    {
        yield 'returns the value from the array' => [
            ['FOO' => 'bar'],
            'FOO',
            'bar',
        ];
        yield 'returns null for empty variable' => [
            ['FOO' => ''],
            'FOO',
            null,
        ];
        yield 'returns null for non-existent variable' => [
            ['FOO' => ''],
            'BAR',
            null,
        ];
    }

    public function testSetValue(): void
    {
        $key = uniqid(__METHOD__);
        $value = 'success!';
        $provider = new GetenvProvider();
        $provider->set($key, $value);
        try {
            Assert::assertSame($value, getenv($key));
        } finally {
            putenv($key);
        }
    }

    public function testUnsetValue(): void
    {
        $key = uniqid(__METHOD__);
        $cleanup = $this->populateEnv([$key => 'success!']);

        $provider = new GetenvProvider();
        $provider->unset($key);
        try {
            Assert::assertFalse(getenv($key));
        } finally {
            $cleanup();
        }
    }

    private function populateEnv(array $values): callable
    {
        foreach ($values as $k => $v) {
            putenv("{$k}={$v}");
        }

        return function() use($values) {
            foreach ($values as $k => $v) {
                putenv($k);
            }
        };
    }
}
