<?php

if (!function_exists('toString')) {
    function toString(mixed $value): string
    {
        try {
            return $value instanceof Stringable
            || (is_object($value) && method_exists($value, '__toString'))
                ? $value->__toString()
                : (string)$value;
        } catch (Throwable) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
    }
}
