<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\ServingCustomers;

class ServingCustomers
{
    private ServingCustomersStatus $status = ServingCustomersStatus::Started;
    private array $kitchenOrders = [];
    private bool $menuWasShownStatus = false;
    private array $menuShownCustomer = [];

    public static function create(string $processId)
    {
        return new self($processId);
    }

    private function __construct(public string $processId)
    {
    }

    public function finishService()
    {
        $this->status = ServingCustomersStatus::Ended;
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

    public function getBill(): array
    {
        if (!$this->allOrdersDone()) {
            return [];
        }
        $bill = [
            "sum" => [],
            "items" => [],
        ];
        foreach ($this->kitchenOrders as $kitchenOrderId=>$kitchenOrder){
            $menuId = $kitchenOrder['menuId'];
            $pizzaSize = $kitchenOrder['pizzaSize'];
            $price = $this->menuShownCustomer[$menuId]['size'][$pizzaSize]['price'];
            $currency = $this->menuShownCustomer[$menuId]['size'][$pizzaSize]['currency'];
            $bill['items'][$kitchenOrderId] = [
                'menuId' => $menuId,
                'name' => $this->menuShownCustomer[$menuId]['name'],
                'pizzaSize' => $pizzaSize,
                'price' => $price,
                'currency' => $currency,
            ];
            $bill['sum'][$currency] = match (array_key_exists($currency,$bill['sum'])) {
                true => $bill['sum'][$currency] + $price,
                false => $price,
            };
        }
        return $bill;
    }

}
