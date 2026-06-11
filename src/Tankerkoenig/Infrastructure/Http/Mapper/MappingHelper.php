<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Mapper;

use App\Tankerkoenig\Infrastructure\Http\Exception\MappingException;
use App\Tankerkoenig\Infrastructure\Http\Trait\CastHelper;

trait MappingHelper
{
    use CastHelper;

    /**
     * @param array<mixed> $a
     */
    private static function getString(array $a, string $field): string
    {
        $value = $a[$field] ?? throw MappingException::missingField($field);
        if (!is_string($value)) {
            throw MappingException::invalidType($field, 'string', gettype($value));
        }
        return $value;
    }

    /**
     * @param array<mixed> $a
     */
    private static function getInt(array $a, string $field): int
    {
        $value = $a[$field] ?? throw MappingException::missingField($field);
        if (!is_int($value)) {
            throw MappingException::invalidType($field, 'int', gettype($value));
        }
        return $value;
    }

    /**
     * @param array<mixed> $a
     */
    private static function getFloat(array $a, string $field): float
    {
        $value = $a[$field] ?? throw MappingException::missingField($field);
        if (!is_float($value) && !is_int($value)) {
            throw MappingException::invalidType($field, 'float', gettype($value));
        }
        return (float) $value;
    }

    /**
     * @param array<mixed> $a
     */
    private static function getBool(array $a, string $field): bool
    {
        $value = $a[$field] ?? throw MappingException::missingField($field);
        if (!is_bool($value)) {
            throw MappingException::invalidType($field, 'bool', gettype($value));
        }
        return $value;
    }

    /**
     * @param array<mixed> $a
     * @return array<mixed>
     */
    private static function getArray(array $a, string $field): array
    {
        $value = $a[$field] ?? throw MappingException::missingField($field);
        if (!is_array($value)) {
            throw MappingException::invalidType($field, 'array', gettype($value));
        }
        return $value;
    }

    /**
     * @param array<mixed> $a
     */
    private static function getNullableFloat(array $a, string $field): ?float
    {
        $value = $a[$field] ?? null;
        if ($value === null || $value === false) {
            return null;
        }
        if (!is_float($value) && !is_int($value)) {
            throw MappingException::invalidType($field, 'float', gettype($value));
        }
        return (float) $value;
    }

    /**
     * @param array<mixed> $a
     */
    private static function getNullableString(array $a, string $field): ?string
    {
        $value = $a[$field] ?? null;
        if ($value === null) {
            return null;
        }
        if (!is_string($value)) {
            throw MappingException::invalidType($field, 'string', gettype($value));
        }
        return $value;
    }
}
