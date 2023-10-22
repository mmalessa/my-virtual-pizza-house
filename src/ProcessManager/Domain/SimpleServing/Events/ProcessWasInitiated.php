<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\SimpleServing\Events;

use App\ProcessManager\Domain\SimpleServing\SimpleServingId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

class ProcessWasInitiated implements SerializablePayload
{
    public function __construct(
        public readonly SimpleServingId $id
    ) {
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString()
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new static(SimpleServingId::fromString($payload['id']));
    }
}
