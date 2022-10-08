<?php declare(strict_types=1);

namespace Xdg\Environment;

/**
 * Environment provider that fetches variables from a chain of provider,
 * returning the first non-null value found.
 */
final class ChainProvider implements EnvironmentProviderInterface
{
    /**
     * @var EnvironmentProviderInterface[]
     */
    private readonly array $providers;

    public function __construct(EnvironmentProviderInterface ...$providers)
    {
        $this->providers = $providers;
    }

    public function get(string $key): ?string
    {
        foreach ($this->providers as $provider) {
            if (null !== $value = $provider->get($key)) {
                return $value;
            }
        }

        return null;
    }

    public function set(string $key, string $value): void
    {
        foreach ($this->providers as $provider) {
            $provider->set($key, $value);
        }
    }

    public function unset(string $key): void
    {
        foreach ($this->providers as $provider) {
            $provider->unset($key);
        }
    }
}
