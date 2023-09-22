<?php

declare(strict_types=1);

namespace Mmalessa\SomeTools;

use Psr\Log\LoggerInterface;

class SomeDelay implements SomeDelayInterface
{
    public function __construct(
        public readonly int $delayTime,
        public readonly LoggerInterface $logger
    ) {
    }

    public function delay(): void {
        $this->logger->info(sprintf("** Sleep %d sec **", $this->delayTime));
        sleep($this->delayTime);
    }
}
