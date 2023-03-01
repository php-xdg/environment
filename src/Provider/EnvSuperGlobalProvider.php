<?php declare(strict_types=1);

namespace Xdg\Environment\Provider;

use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Exception\NonScalarValueException;

/**
 * Environment provider that fetches variables from the $_ENV super-global.
 */
final class EnvSuperGlobalProvider implements EnvironmentProviderInterface
{
    public function __construct(
        private readonly bool $emptyStringIsNull = true,
    ) {
    }

    public function get(string $key): ?string
    {
        return match ($value = $_ENV[$key] ?? null) {
            null => null,
            '' => $this->emptyStringIsNull ? null : '',
            false => '0',
            default => is_scalar($value) ? (string)$value : throw NonScalarValueException::of($key, $value),
        };
    }

    public function set(string $key, string $value): void
    {
        $_ENV[$key] = $value;
    }

    public function unset(string $key): void
    {
        unset($_ENV[$key]);
    }
}
