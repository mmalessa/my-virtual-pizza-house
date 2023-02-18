<?php

declare(strict_types=1);

namespace App\Waiter\UI\Display;

use App\Waiter\Domain\CommunicatorInterface;

class Communicator implements CommunicatorInterface
{
    public function showMenu(array $menu)
    {
        echo "------ Here is our menu ------\n";
        foreach ($menu as $itemId=>$item) {
            printf(
                "[%s] %-25s  price: %s\n",
                $itemId,
                $item['name'],
                implode(", ", $this->getPizzaPrices($item['size']))
            );
        }
        echo "------------------------------\n";
    }
    private function getPizzaPrices(array $sizes): array
    {
        $info = [];
        foreach ($sizes as $sizeId=>$size) {
            $info[] = sprintf("%s %d %s", $sizeId, $size['price'], $size['currency']);
        }
        return $info;
    }

    public function infoAboutServedPizzas(array $pizzas) {
        echo "------- I am happy to serve all pizzas --------\n";
        foreach ($pizzas as $pizza) {
            printf("(%s) %s %s\n", $pizza['menuId'], $pizza['name'], $pizza['pizzaSize']);
        }
        echo "-----------------------------------------------\n";
    }
}