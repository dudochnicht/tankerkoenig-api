<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Trait;

trait CastHelper
{
    private static function castToString(mixed $value): string
    {
        return match (true) {
            is_string($value) => $value,
            is_int($value)    => (string) $value,
            is_float($value)  => (string) $value,
            is_bool($value)   => $value ? 'true' : 'false',
            is_null($value)   => 'null',
            is_array($value)  => 'array(' . count($value) . ')',
            is_object($value) => $value instanceof \Stringable ? (string) $value : $value::class,
            default           => 'unknown',
        };
    }
}
