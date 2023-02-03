<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Provider\ArrayProvider;
use Xdg\Environment\Provider\ChainProvider;
use function PHPUnit\Framework\exactly;

final class ChainProviderTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue(array $envs, string $key, ?string $expected): void
    {
        $provider = new ChainProvider(
            ...array_map(fn($env) => new ArrayProvider($env), $envs)
        );
        Assert::assertSame($expected, $provider->get($key));
    }

    public function getValueProvider(): iterable
    {
        yield 'returns null for empty chain' => [
            [],
            'foo',
            null,
        ];
        yield 'returns null when key not found' => [
            [['a' => 'b'], ['c' => 'd']],
            'foo',
            null,
        ];
        yield 'returns the first value in the chain' => [
            [[], ['nope' => 42], ['foo' => 'bar'], ['foo' => 'nope']],
            'foo',
            'bar',
        ];
    }

    public function testSetValue(): void
    {
        $key = 'foo';
        $value = 'bar';

        $provider = $this->createMock(EnvironmentProviderInterface::class);
        $provider->expects(exactly(2))
            ->method('set')
            ->with($key, $value)
        ;

        $chain = new ChainProvider($provider, $provider);
        $chain->set($key, $value);
    }

    public function testUnsetValue(): void
    {
        $key = 'foo';

        $provider = $this->createMock(EnvironmentProviderInterface::class);
        $provider->expects(exactly(2))
            ->method('unset')
            ->with($key)
        ;

        $chain = new ChainProvider($provider, $provider);
        $chain->unset($key);
    }
}
