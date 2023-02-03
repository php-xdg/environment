<?php declare(strict_types=1);

namespace Xdg\Environment\Provider;

use Xdg\Environment\EnvironmentProviderInterface;

/**
 * Environment provider that fetches variables from the supplied provider and caches the result.
 */
final class CachingProvider implements EnvironmentProviderInterface
{
    private array $cache = [];

    public function __construct(
        private readonly EnvironmentProviderInterface $provider,
    ) {
    }

    public function get(string $key): ?string
    {
        return $this->cache[$key] ??= \array_key_exists($key, $this->cache) ? null : $this->provider->get($key);
    }

    public function set(string $key, string $value): void
    {
        $this->provider->set($key, $value);
        $this->cache[$key] = $value;
    }

    public function unset(string $key): void
    {
        $this->provider->unset($key);
        unset($this->cache[$key]);
    }
}
