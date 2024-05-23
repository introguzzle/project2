<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class Requests
{
    /**
     * @template T
     * @param Request $request
     * @param class-string<T> $class
     * @return T
     * @throws ReflectionException
     */
    public static function compact(
        Request $request,
        string  $class
    )
    {
        $requestBody = $request->all();

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        $args = [];

        if (!$constructor) {
            return $reflection->newInstanceArgs();
        }

        $delimiter = str_contains((string)(array_values($requestBody)[0]), '-')
            ? '-'
            : '_';

        foreach ($constructor->getParameters() as $parameter) {
            $parameterName = $parameter->getName();

            $input = Str::snake($parameterName, $delimiter);

            $args[] = $requestBody[$input] ??
                ($parameter->getType()->getName() === 'bool'
                    ? false
                    : null
                );
        }

        return $reflection->newInstanceArgs($args);
    }
}
