<?php declare(strict_types=1);

namespace Xdg\Environment\Provider;

use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Exception\NonScalarValueException;

/**
 * Environment provider that fetches variables from the $_SERVER super-global.
 */
final class ServerSuperGlobalProvider implements EnvironmentProviderInterface
{
    public function __construct(
        private readonly bool $emptyStringIsNull = true,
        private readonly bool $ignoreHttp = true,
    ) {
    }

    public function get(string $key): ?string
    {
        if ($this->ignoreHttp && str_starts_with($key, 'HTTP_')) {
            return null;
        }
        return match ($value = $_SERVER[$key] ?? null) {
            null => null,
            '' => $this->emptyStringIsNull ? null : '',
            false => '0',
            default => is_scalar($value) ? (string)$value : throw NonScalarValueException::of($key, $value),
        };
    }

    public function set(string $key, string $value): void
    {
        if ($this->ignoreHttp && str_starts_with($key, 'HTTP_')) {
            return;
        }
        $_SERVER[$key] = $value;
    }

    public function unset(string $key): void
    {
        if ($this->ignoreHttp && str_starts_with($key, 'HTTP_')) {
            return;
        }
        unset($_SERVER[$key]);
    }
}
