<?php

namespace App\Utils;

use App\Models\Model;
use Exception;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use ReflectionClass;
use ReflectionException;

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

        if (!is_subclass_of($class, EloquentModel::class)) {
            throw new Exception("$class is not an " . EloquentModel::class);
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
