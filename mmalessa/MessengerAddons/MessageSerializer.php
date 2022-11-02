<?php

namespace Mmalessa\MessengerAddons;

use App\Waiter\SomeMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class MessageSerializer implements SerializerInterface
{
    private CONST ALLOWED_TYPES = ["string", "int", "float", "array"];

    public function __construct(
        private string $targetBusName,
        private string $messageClassPrefix
    )
    {
        $this->messageClassPrefix = sprintf("%s\\", rtrim($this->messageClassPrefix, '\\'));
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'type' => $this->getMessageTypeFromClassName(get_class($message)),
            'payload' => $this->autoSerializeMessage($message)
        ];
        return [
            'body' => json_encode($body),
            'headers' => $headers
        ];
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];
        try {
            $payload = $this->autoDeserializeMessage($body);
            $envelope = new Envelope($payload);
            return $envelope;
        } catch (\Exception $e) {
            throw $e; //FIXME
        }
    }

    private function getMessageTypeFromClassName(string $className): string
    {
        echo "CN: $className\n";
        echo "PF: " . $this->messageClassPrefix . PHP_EOL;
        $pattern =sprintf("/^%s/", preg_quote($this->messageClassPrefix));
        echo "P: " . $pattern . PHP_EOL;
        return preg_replace($pattern,'',$className,1);
    }

    private function getClassNameFromMessageType(string $messageType): string
    {
        return sprintf("%s%s", $this->messageClassPrefix, $messageType);
    }

    private function autoSerializeMessage($message): array
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
            if(!in_array($parameterType, self::ALLOWED_TYPES)) {
                throw new \InvalidArgumentException(sprintf(
                    "There is '%s' variable with an illegal type '%s' in the '%s' object. Types allowed: [%s]",
                    $parameterName,
                    $parameterType,
                    get_class($message),
                    implode(", ", self::ALLOWED_TYPES)
                ));
            }
            $serializedMessage[$parameterName] = $message->{$parameterName};
        }
        return $serializedMessage;
    }

    private function autoDeserializeMessage(string $body)
    {
        $arrayBody = json_decode($body, true);
        $type = $arrayBody['type'];
        $payload = $arrayBody['payload'];

        $className = $this->getClassNameFromMessageType($type);
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
    }
}