<?php

declare(strict_types=1);

namespace App\OrderManager\Domain;

class TableService
{
    private TableServiceStatus $status;
    private array $kitchenOrders;
    private bool $menuWasShownStatus;
    public function __construct(public readonly string $sagaId)
    {
        $this->status = TableServiceStatus::Started;
        $this->kitchenOrders = [];
        $this->menuWasShownStatus = false;
    }

    public function canShowMenu(): bool
    {
        return !$this->menuWasShownStatus;
    }

    public function menuWasShown()
    {
        $this->menuWasShownStatus = true;
    }

    public function addKitchenOrder(string $kitchenOrderId)
    {
        $this->kitchenOrders[$kitchenOrderId] = KitchenOrderStatus::Todo;
    }

    public function kitchenOrderDone(string $kitchenOrderId)
    {
        $this->kitchenOrders[$kitchenOrderId] = KitchenOrderStatus::Done;
    }

    public function allOrdersDone(): bool
    {
        foreach ($this->kitchenOrders as $kitchenOrderId=>$kitchenOrderStatus) {
            if ($kitchenOrderStatus === KitchenOrderStatus::Todo) {
                return false;
            }
        }
        return true;
    }

}