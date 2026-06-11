<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Exception;

use App\Tankerkoenig\Infrastructure\Http\Trait\CastHelper;

final class MappingException extends \RuntimeException
{
    use CastHelper;

    public static function missingField(string $field): self
    {
        return new self(sprintf("Required field '%s' is missing in API response", $field));
    }

    public static function invalidType(string $field, string $expected, string $given): self
    {
        return new self(sprintf("Field '%s' expected type %s, %s given", $field, $expected, $given));
    }

    public static function invalidJson(): self
    {
        return new self('API response could not be decoded as JSON');
    }

    public static function invalidEnumValue(string $field, mixed $value): self
    {

        return new self(sprintf("Field '%s' has invalid value '%s'", $field, self::castToString($value)));
    }
}
