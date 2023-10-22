<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\SimpleServing\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class OrderPlaceed implements SerializablePayload
{
    public function __construct(
        public readonly array $order
    ) {
    }

    public function toPayload(): array
    {
        return [
            'order' => $this->order
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload['order']);
    }
}
