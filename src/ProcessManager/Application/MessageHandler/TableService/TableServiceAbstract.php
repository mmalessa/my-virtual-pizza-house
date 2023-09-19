<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\MessageHandler\TableService;

use App\ProcessManager\Domain\TableServiceRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class TableServiceAbstract
{
    public function __construct(
        protected readonly MessageBusInterface $messageBus,
        protected readonly LoggerInterface $logger,
        protected readonly TableServiceRepositoryInterface $tableServiceRepository
    )
    {
    }
}
