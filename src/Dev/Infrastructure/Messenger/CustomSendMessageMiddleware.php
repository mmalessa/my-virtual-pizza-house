<?php

namespace App\Dev\Infrastructure\Messenger;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class CustomSendMessageMiddleware implements MiddlewareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private TransportInterface $transport
    ) {
        $this->logger = new NullLogger();
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->transport->send($envelope);
        print_r($envelope);
        return $envelope;
    }
}