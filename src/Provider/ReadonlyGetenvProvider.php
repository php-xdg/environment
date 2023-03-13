<?php declare(strict_types=1);

namespace Xdg\Environment\Provider;

use Xdg\Environment\EnvironmentProviderInterface;

/**
 * Readonly version of the {@see GetenvProvider}.
 */
final class ReadonlyGetenvProvider implements EnvironmentProviderInterface
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
    }

    public function unset(string $key): void
    {
    }
}
