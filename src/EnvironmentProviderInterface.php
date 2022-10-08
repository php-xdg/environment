<?php declare(strict_types=1);

namespace Xdg\Environment;

interface EnvironmentProviderInterface
{
    public function get(string $key): ?string;
    public function set(string $key, string $value): void;
    public function unset(string $key): void;
}
