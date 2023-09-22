<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddons;

use Mmalessa\MessengerAddons\Error\ClassDoesNotExistError;

class DefaultMessageSerializer implements MessageSerializerInterface
{
    private CONST ALLOWED_SIMPLE_TYPES = ["string", "int", "float", "array"];

    public function __construct(
        private string $messageClassPrefix
    )
    {
        $this->messageClassPrefix = sprintf("%s\\", rtrim($this->messageClassPrefix, '\\'));
    }

    public function getMessageClassPrefix(): string
    {
        return $this->messageClassPrefix;
    }

    public function serialize(MessageInterface $message): array
    {
        if(method_exists($message, 'serialize')) {
            return $message->serialize();
        }
        return $this->autoSerializeMessage($message);
    }

    public function deserialize(string $type, array $payload): MessageInterface
    {
        $className = MessagePrefix::add($type, $this->messageClassPrefix);
        if(method_exists($className, 'deserialize')) {
            return $className::deserialize($payload);
        }
        return $this->autoDeserializeMessage($type, $payload);
    }

    private function autoSerializeMessage(MessageInterface $message): array
    {
        $reflection = new \ReflectionClass(get_class($message));
        $constructor = $reflection->getConstructor();
        if (null === $constructor) {
            return [];
        }
        $parameters = $constructor->getParameters();
        $serializedMessage = [];
        /** @var \ReflectionParameter $parameter */
        foreach($parameters as $parameter) {
            $parameterName = $parameter->getName();
            $parameterType = $parameter->getType();
            if(in_array($parameterType, self::ALLOWED_SIMPLE_TYPES)) {
                $serializedMessage[$parameterName] = $message->{$parameterName};
            } elseif ((new \ReflectionClass($parameterType))->implementsInterface(\Stringable::class)) {
                $serializedMessage[$parameterName] = (string)$message->{$parameterName};
            } else {
                throw new \InvalidArgumentException(sprintf(
                    "There is '%s' variable with an illegal type '%s' in the '%s' object. "
                    ."The type must be: [%s] or must implement an interface: [%s]",
                    $parameterName,
                    $parameterType,
                    get_class($message),
                    implode(", ", self::ALLOWED_SIMPLE_TYPES),
                    \Stringable::class
                ));
            }
        }
        return $serializedMessage;
    }

    private function autoDeserializeMessage(string $type, array $payload): MessageInterface
    {
        try {
            $className = MessagePrefix::add($type, $this->messageClassPrefix);
            if (!class_exists($className)) {
                throw new ClassDoesNotExistError(sprintf("Class %s does not exists", $className));
            }
            $classParameters = [];
            $reflection = new \ReflectionClass($className);
            $constructor = $reflection->getConstructor();
            if (null !== $constructor) {
                $parameters = $constructor->getParameters();
                foreach ($parameters as $parameter) {
                    $parameterName = $parameter->getName();
                    $parameterValue = $payload[$parameterName] ?? null;
                    $classParameters[] = $parameterValue;
                }
            }
            $message = $reflection->newInstanceArgs($classParameters);
            return $message;
        } catch (ClassDoesNotExistError $e) {
            //TODO
            throw $e;
        } catch (\Exception $e) {
            //TODO
            throw $e;
        }
    }
}
