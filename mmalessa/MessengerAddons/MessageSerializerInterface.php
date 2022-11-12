<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddons;

interface MessageSerializerInterface
{
    public function serialize(MessageInterface $message): array;
    public function deserialize(string $type, array $payload): MessageInterface;
    public function getMessageClassPrefix(): string;
}