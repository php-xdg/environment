<?php declare(strict_types=1);

namespace Xdg\Environment\Exception;

final class NonScalarValueException extends \UnexpectedValueException implements XdgEnvironmentException
{
    public static function of(string $key, mixed $value): self
    {
        return new self(sprintf(
            'Unexpected non-scalar value of type "%s" for environment variable "%s".',
            get_debug_type($value),
            $key,
        ));
    }
}
