<?php

namespace App\Other;

use App\Models\Core\Model;
use Exception;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class ModelRecordResolver
{
    /**
     * @param string $class
     * @param bool $includeConstantNames
     * @return array<string|int, string>
     * @throws ReflectionException
     * @throws Exception
     */
    public static function getRecords(
        string $class,
        bool   $includeConstantNames = false
    ): array
    {
        $reflection = new ReflectionClass($class);

        if (!is_subclass_of($class, Model::class)) {
            throw new RuntimeException("$class is not a subclass of " . Model::class);
        }

        $constants = $reflection->getReflectionConstants();
        $dbRecords = [];

        foreach ($constants as $constant) {
            $docComment = $constant->getDocComment();

            if ($docComment && str_contains($docComment, Model::DB_RECORD)) {
                $dbRecords[$constant->getName()] = $constant->getValue();
            }
        }

        return $includeConstantNames
            ? $dbRecords
            : array_values($dbRecords);
    }
}
