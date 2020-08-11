<?php


namespace App\Helpers\Interfaces;

use Illuminate\Support\Collection;

/**
 * Interface OrdersServiceInterface
 * @package App\Helpers\Interfaces
 */
interface OrdersServiceInterface
{
    /**
     * get imported orders
     * @return Collection
     */
    public function getOrders(): Collection;

    /**
     * get imported orders count
     * @return int
     */
    public function getOrdersCount(): int;

    /**
     * delete orders
     */
    public function deleteOrders(): void;
}
