<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getFilteredOrders(array $requestData): LengthAwarePaginator;

    public function addItem($order, array $data): OrderItem;

    public function updateTotal($orderId, $total): int;

    public function findWithItems(int $id): Order;

    public function updateStatus($id, $status): int;
}
