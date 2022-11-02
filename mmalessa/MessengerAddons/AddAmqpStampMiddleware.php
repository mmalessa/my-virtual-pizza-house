<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddons;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class AddAmqpStampMiddleware implements MiddlewareInterface
{
    public function __construct(
        private string $messageClassPrefix
    )
    {
        $this->messageClassPrefix = sprintf("%s\\", rtrim($this->messageClassPrefix, '\\'));
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /** @var MessageInterface $message */
        $message = $envelope->getMessage();
        $routingKey = $this->getRoutingKeyFromClassName(get_class($message));
        $attributes = [
            'delivery_mode' => AMQP_DURABLE,
        ];
        $envelope = $envelope->with(new AmqpStamp($routingKey, AMQP_NOPARAM, $attributes));
        return $stack->next()->handle($envelope, $stack);
    }

    private function getRoutingKeyFromClassName(string $className): string
    {
        $messageType = MessagePrefix::remove($className, $this->messageClassPrefix);
        return str_replace('\\', '.', $messageType);
    }
}