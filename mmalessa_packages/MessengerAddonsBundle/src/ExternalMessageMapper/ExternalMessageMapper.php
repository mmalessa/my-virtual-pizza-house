<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddonsBundle\ExternalMessageMapper;

class ExternalMessageMapper
{
    private array $schemaToClassNameList = [];
    private array $classNameToSchemaList = [];

    public function register(
        string $className,
        string $schemaId,
    ): void {

        if (array_key_exists($schemaId, $this->schemaToClassNameList)) {
            throw new \InvalidArgumentException(sprintf(
                "Schema %s already exists in schemaToClassList",
                $schemaId
            ));
        }

        if (array_key_exists($schemaId, $this->classNameToSchemaList)) {
            throw new \InvalidArgumentException(sprintf(
                "Class %s already exists in classToSchemaList",
                $className
            ));
        }

        $this->classNameToSchemaList[$className] = $schemaId;
        $this->schemaToClassNameList[$schemaId] = $className;
    }

    public function getClassName(string $schemaId): string
    {
        return $this->schemaToClassNameList[$schemaId];
    }

    public function getSchemaId(string $className): string
    {
        return $this->classNameToSchemaList[$className];
    }

    public function devSerialize(): array
    {
        return [
            $this->schemaToClassNameList,
            $this->classNameToSchemaList,
        ];
    }
}
