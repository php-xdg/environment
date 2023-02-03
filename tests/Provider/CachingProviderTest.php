<?php declare(strict_types=1);

namespace Xdg\Environment\Tests\Provider;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Provider\CachingProvider;
use function PHPUnit\Framework\exactly;
use function PHPUnit\Framework\once;

final class CachingProviderTest extends TestCase
{
    #[DataProvider('getValueProvider')]
    public function testGetValue(string $key, ?string $expected): void
    {
        $provider = $this->createMock(EnvironmentProviderInterface::class);
        $provider->expects(once())
            ->method('get')
            ->with($key)
            ->willReturn($expected)
        ;

        $cache = new CachingProvider($provider);
        Assert::assertSame($expected, $cache->get($key));
        Assert::assertSame($expected, $cache->get($key));
    }

    public static function getValueProvider(): iterable
    {
        yield 'null value' => ['foo', null];
        yield 'non-null value' => ['foo', 'bar'];
    }

    public function testSetValue(): void
    {
        $key = 'foo';
        $provider = $this->createMock(EnvironmentProviderInterface::class);
        $provider->expects(exactly(2))
            ->method('set')
            ->with($key)
        ;

        $cache = new CachingProvider($provider);
        $cache->set($key, 'bar');
        Assert::assertSame('bar', $cache->get($key));
        $cache->set($key, 'baz');
        Assert::assertSame('baz', $cache->get($key));
    }

    public function testUnsetValue(): void
    {
        $key = 'foo';
        $provider = $this->createMock(EnvironmentProviderInterface::class);
        $provider->expects(once())
            ->method('unset')
            ->with($key)
        ;
        $cache = new CachingProvider($provider);
        $cache->set($key, 'bar');
        $cache->unset($key);
        Assert::assertNull($cache->get($key));
    }
}
