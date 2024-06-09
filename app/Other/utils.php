<?php

use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;

if (!function_exists('toString')) {
    function toString(mixed $value): string
    {
        try {
            return $value instanceof Stringable
            || (is_object($value) && method_exists($value, '__toString'))
                ? $value->__toString()
                : (string) $value;
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
        return json_encode(
            $value,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | $flags
        );
    }
}

if (!function_exists('formatDate')) {
    function formatDate(
        ?CarbonInterface $date,
        bool             $includeTime = false
    ): string
    {
        if ($date === null) {
            return 'Отсутствует';
        }

        return $includeTime
            ? $date->format('d.m.Y H:i:s')
            : $date->format('d.m.Y');
    }
}

if (!function_exists('uniqueId')) {
    function uniqueId(
        string $prefix = 'a',
        bool   $moreEntropy = true
    ): string
    {
        return uniqid($prefix, $moreEntropy);
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

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber(string|int $number): string
    {
        $number = (string) $number;
        $number = preg_replace('/\D/', '', $number);

        $formattedNumber = '+7 ';

        $formattedNumber .= substr($number, 1, 3) . ' ';
        $formattedNumber .= substr($number, 4, 3) . '-';
        $formattedNumber .= substr($number, 7, 2) . '-';
        $formattedNumber .= substr($number, 9, 2);

        return $formattedNumber;
    }
}

if (!function_exists('viewAdmin')) {
    function viewAdmin(string $view): View
    {
        return view('admin.' . $view);
    }
}

if (!function_exists('viewClient')) {
    function viewClient(
        string $view,
        ?string $version = null
    ): View
    {
        if ($version === null) {
            $version = env('FRONTEND_VERSION', 'v2');
        }

        return view('client.' . $version . '.' . $view);
    }
}


