<?php declare(strict_types=1);

namespace Xdg\Environment\Exception;

final class UnexpectedValueException extends EnvironmentException
{
    public static function nonScalar(string $key, mixed $value): self
    {
        return new self(sprintf(
            'Unexpected non-scalar value of type "%s" for environment variable "%s".',
            get_debug_type($value),
            $key,
        ));
    }
}
