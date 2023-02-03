<?php declare(strict_types=1);

namespace Xdg\Environment\Provider;

use Xdg\Environment\EnvironmentProviderInterface;

/**
 * Environment provider that fetches variables using {@see getenv()}.
 */
final class GetenvProvider implements EnvironmentProviderInterface
{
    public function get(string $key): ?string
    {
        return match ($value = getenv($key)) {
            '', false => null,
            default => $value,
        };
    }

    public function set(string $key, string $value): void
    {
        putenv("{$key}={$value}");
    }

    public function unset(string $key): void
    {
        putenv($key);
    }
}
