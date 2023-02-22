<?php

declare(strict_types=1);

namespace App\OrderManager\Domain;

class TableService
{
    private TableServiceStatus $status;
    private array $kitchenOrders;
    private bool $menuWasShownStatus;
    private array $menuShownCustomer;

    private array $orderList;
    public function __construct(public readonly string $sagaId)
    {
        $this->status = TableServiceStatus::Started;
        $this->kitchenOrders = [];
        $this->menuWasShownStatus = false;
        $this->menuShownCustomer = [];
        $this->orderList = [];
    }

    public function thisMenuWasShownToCustomer(array $menuShownCustomer)
    {
        $this->menuShownCustomer = $menuShownCustomer;
    }
    public function canShowMenu(): bool
    {
        return !$this->menuWasShownStatus;
    }

    public function menuWasShown()
    {
        $this->menuWasShownStatus = true;
    }

    public function addKitchenOrder(string $kitchenOrderId, string $menuId, string $pizzaSize)
    {
        $this->kitchenOrders[$kitchenOrderId] = [
            'status' => KitchenOrderStatus::Todo,
            'menuId' => $menuId,
            'pizzaSize' => $pizzaSize,
        ];
    }

    public function kitchenOrderDone(string $kitchenOrderId)
    {
        $this->kitchenOrders[$kitchenOrderId]['status'] = KitchenOrderStatus::Done;
    }

    public function allOrdersDone(): bool
    {
        foreach ($this->kitchenOrders as $kitchenOrderId=>$kitchenOrder) {
            if ($kitchenOrder['status'] === KitchenOrderStatus::Todo) {
                return false;
            }
        }
        return true;
    }

    public function getPizzasToServe(): array
    {
        if (!$this->allOrdersDone()) {
            return [];
        }
        $pizzasToServe = [];
        foreach ($this->kitchenOrders as $kitchenOrderId=>$kitchenOrder){
            $menuId = $kitchenOrder['menuId'];
            $pizzasToServe[$kitchenOrderId] = [
                'menuId' => $menuId,
                'name' => $this->menuShownCustomer[$menuId]['name'],
                'pizzaSize' => $kitchenOrder['pizzaSize'],
            ];
        }
        return $pizzasToServe;
    }

}