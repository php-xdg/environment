<?php declare(strict_types=1);

namespace Xdg\Environment\Provider;

use Xdg\Environment\EnvironmentProviderInterface;
use Xdg\Environment\Exception\NonScalarValueException;

/**
 * Environment provider that fetches variables from the supplied array.
 */
final class ArrayProvider implements EnvironmentProviderInterface
{
    public function __construct(
        private array $env,
    ) {
    }

    public function get(string $key): ?string
    {
        return match ($value = $this->env[$key] ?? null) {
            null, '', false => null,
            default => is_scalar($value) ? (string)$value : throw NonScalarValueException::of($key, $value),
        };
    }

    public function set(string $key, string $value): void
    {
        $this->env[$key] = $value;
    }

    public function unset(string $key): void
    {
        unset($this->env[$key]);
    }
}
