<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function addItem($order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    public function updateTotal($orderId, $total): int
    {
        return $this->model->where('id', $orderId)->update([
            'total_amount' => $total,
        ]);
    }

    public function findWithItems(int $id): Order
    {
        return $this->model->with(['shop', 'items', 'items.product', 'status'])->findOrFail($id);
    }

    public function updateStatus($id, $status): int
    {
        return $this->model->where('id', $id)->update([
            'status_id' => $status,
        ]);
    }

    public function getFilteredOrders(array $requestData): LengthAwarePaginator
    {
        return $this->model->with(['shop', 'items', 'items.product', 'status'])
            ->when($requestData['shop_id'], fn ($q) => $q->where('shop_id', $requestData['shop_id']))
            ->when($requestData['status'], fn ($q) => $q->whereHas('status', fn ($q) => $q->where('key', $requestData['status'])))
            ->when(isset($requestData['from']), fn ($q) => $q->whereDate('placed_at', '>=', $requestData['from']))
            ->when(isset($requestData['to']), fn ($q) => $q->whereDate('placed_at', '<=', $requestData['to']))
            ->paginate(10);
    }
}
