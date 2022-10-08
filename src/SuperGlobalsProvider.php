<?php declare(strict_types=1);

namespace Xdg\Environment;

use Xdg\Environment\Exception\UnexpectedValueException;

/**
 * Environment provider that fetches variables from $_SERVER and $_ENV super-globals.
 */
final class SuperGlobalsProvider implements EnvironmentProviderInterface
{
    public function get(string $key): ?string
    {
        return match ($value = $_SERVER[$key] ?? $_ENV[$key] ?? null) {
            null, '', false => null,
            default => is_scalar($value) ? (string)$value : throw UnexpectedValueException::nonScalar($key, $value),
        };
    }

    public function set(string $key, string $value): void
    {
        $_SERVER[$key] = $_ENV[$key] = $value;
    }

    public function unset(string $key): void
    {
        unset($_SERVER[$key], $_ENV[$key]);
    }
}
