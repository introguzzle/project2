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
        string $class
    ): object
    {
        $requestBody = $request->all();

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        $args = [];

        if (!$constructor) {
            return $reflection->newInstanceArgs();
        }

        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();
            $propertyName = str_contains($name, '-')
                ? Str::snake($name, '-')
                : Str::snake($name);

            $args[] = $requestBody[$propertyName] ??
                ($parameter->getType()->getName() === 'bool'
                    ? false
                    : null
                );
        }

        return $reflection->newInstanceArgs($args);
    }
}
