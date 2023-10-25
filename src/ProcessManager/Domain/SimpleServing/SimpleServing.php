<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\SimpleServing;

use App\ProcessManager\Domain\SimpleServing\Events\OrderPlaceed;
use App\ProcessManager\Domain\SimpleServing\Events\ProcessWasInitiated;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class SimpleServing implements AggregateRoot
{
    use AggregateRootBehaviour;
    private array $order = [];

    public static function initiate(SimpleServingId $id): self
    {
        $process = new static($id);
        $process->recordThat(new ProcessWasInitiated($id));
        return $process;
    }

    public function applyProcessWasInitiated(ProcessWasInitiated $event): void
    {
    }

    public function placeOrder(array $order): void
    {
        $this->recordThat(new OrderPlaceed($order));
    }

    public function applyOrderPlaceed(OrderPlaceed $event): void
    {
        $this->order = array_merge($this->order, $event->order);
    }

    public function getOrder(): array
    {
        return $this->order;
    }
}
