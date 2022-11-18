<?php

declare(strict_types=1);

namespace Mmalessa\AutoSerializer;

class AutoSerializer
{
    public static function serialize(object $obj): array
    {
        $reflection = new \ReflectionClass(get_class($obj));
        $serialized = [
            'item_type' => $reflection->getShortName(),
        ];
        $constructor = $reflection->getConstructor();
        if (null === $constructor) {
            return $serialized;
        }
        $parameters = $constructor->getParameters();
        /** @var \ReflectionParameter $parameter */
        foreach($parameters as $parameter) {
            $parameterName = $parameter->getName();
            $parameterType = $parameter->getType()->getName();
            if(in_array($parameterType, ["int", "float", "string", "array"])) {
                $serialized[$parameterName] = $obj->{$parameterName};
            } elseif($parameterType === "bool") {
                $serialized[$parameterName] = $obj->{$parameterName} ? "true" : "false";
            } elseif (enum_exists($parameterType)) {
                $serialized[$parameterName] = $obj->{$parameterName}->name;
            } else {
                throw new \InvalidArgumentException(sprintf(
                    "There is '%s' variable with an illegal type '%s' in the '%s' object",
                    $parameterName,
                    $parameterType,
                    self::class
                ));
            }
        }
        return $serialized;
    }
}