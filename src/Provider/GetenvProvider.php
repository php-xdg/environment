<?php declare(strict_types=1);

namespace Xdg\Environment\Provider;

use Xdg\Environment\EnvironmentProviderInterface;

/**
 * Environment provider that reads variables using {@see getenv()},
 * and uses {@see putenv()} for writing.
 */
final class GetenvProvider implements EnvironmentProviderInterface
{
    public function __construct(
        private readonly bool $emptyStringIsNull = true,
    ) {
    }

    public function get(string $key): ?string
    {
        return match ($value = getenv($key)) {
            false => null,
            '' => $this->emptyStringIsNull ? null : '',
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
