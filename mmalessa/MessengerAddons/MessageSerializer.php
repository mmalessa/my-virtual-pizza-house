<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddons;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
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
        /** @var MessageInterface $message */
        $message = $envelope->getMessage();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'type' => MessagePrefix::remove(get_class($message), $this->messageClassPrefix),
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
            /** @var MessageInterface $payload */
            $payload = $this->autoDeserializeMessage($body);
            $envelope = new Envelope($payload);
            $envelope = $envelope->with(new RedeliveryStamp($this->getRetryCount($headers)));
            $envelope = $envelope->with(new ReceivedStamp(''));
            $envelope = $envelope->with(new BusNameStamp($this->targetBusName));
            return $envelope;
        } catch (\Exception $e) {
            // TODO
            throw $e;
        }
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

    private function autoDeserializeMessage(string $body): MessageInterface
    {
        try {
            $arrayBody = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            $type = $arrayBody['type'];
            $payload = $arrayBody['payload'];

            $className = MessagePrefix::add($type, $this->messageClassPrefix);
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
        } catch (\JsonException $e) {
            //TODO
            throw $e;
        } catch (\Exception $e) {
            //TODO
            throw $e;
        }
    }

    private function getRetryCount(array $headers): int
    {
        if (!array_key_exists('x-death', $headers)) {
            return 0;
        }
        return array_sum(array_column($headers['x-death'],'count'));
    }
}