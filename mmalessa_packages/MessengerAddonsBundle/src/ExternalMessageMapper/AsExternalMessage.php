<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddonsBundle\ExternalMessageMapper;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsExternalMessage
{
    public function __construct(
        public ?string $schemaId = null,
        public ?string $schemaFile = null,
    ) {
    }
}
