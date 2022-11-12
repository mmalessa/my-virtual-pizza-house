<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddons;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class AmqpMessageSerializer implements SerializerInterface
{
    private CONST ALLOWED_TYPES = ["string", "int", "float", "array"];

    public function __construct(
        private string $targetBusName,
        private readonly MessageSerializerInterface $messageSerializer
    )
    {
    }

    public function encode(Envelope $envelope): array
    {
        /** @var MessageInterface $message */
        $message = $envelope->getMessage();
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $body = [
            'type' => MessagePrefix::remove(get_class($message), $this->messageSerializer->getMessageClassPrefix()),
            'payload' => $this->messageSerializer->serialize($message)
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
            $bodyArray = json_decode($body, true);
            $payload = $this->messageSerializer->deserialize($bodyArray['type'], $bodyArray['payload']);
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

    private function getRetryCount(array $headers): int
    {
        if (!array_key_exists('x-death', $headers)) {
            return 0;
        }
        return array_sum(array_column($headers['x-death'],'count'));
    }
}