<?php

use Carbon\CarbonInterface;

if (!function_exists('toString')) {
    function toString(mixed $value): string
    {
        try {
            return $value instanceof Stringable
            || (is_object($value) && method_exists($value, '__toString'))
                ? $value->__toString()
                : (string)$value;
        } catch (Throwable) {
            try {
                return jsonEncode($value);
            } catch (JsonException) {
                return print_r($value, true);
            }
        }
    }
}

if (!function_exists('jsonEncode')) {
    /**
     * @throws JsonException
     */
    function jsonEncode(mixed $value, int $flags = 0): string
    {
        return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | $flags);
    }
}

if (!function_exists('formatDate')) {
    function formatDate(
        CarbonInterface $date,
        bool            $includeTime = false
    ): string
    {
        return $includeTime
            ? $date->format('d.m.Y H:i:s')
            : $date->format('d.m.Y');
    }
}

if (!function_exists('uniqueId')) {
    function uniqueId(): string
    {
        return uniqid('a', true);
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes(
        int $bytes,
        int $precision = 2
    ): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('telegramBot')) {
    function telegramBot(): string
    {
        return 'https://t.me/' . env('TELEGRAM_BOT_USERNAME');
    }
}
