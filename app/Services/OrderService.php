<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Traits\UtilTraits;
use App\Utils\Constants;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    use UtilTraits;

    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    public function createOrder(array $data): OrderResource
    {
        try {
            return DB::transaction(function () use ($data) {

                $order = $this->orderRepository->create([
                    'shop_id' => $data['shop_id'],
                    'status_id' => 1,
                    'total_amount' => 0,
                ]);

                $total = 0;

                foreach ($data['items'] as $item) {

                    $product = $this->productRepository->lockProduct($item['product_id']);

                    if ($product->stock < $item['qty']) {
                        throw new Exception(Constants::$ERROR_MESSAGE['insufficient_stock']." $product[name]",
                            Constants::$ERROR_CODE['unprocessable_entity']);
                    }

                    $subtotal = $product->price * $item['qty'];

                    $this->orderRepository->addItem($order, [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                        'quantity' => $item['qty'],
                        'subtotal' => $subtotal,
                    ]);

                    $this->productRepository->decrementStock($product->id, $item['qty']);

                    $total += $subtotal;
                }

                $this->orderRepository->updateTotal($order->id, $total);

                return new OrderResource($this->orderRepository->findWithItems($order->id));
            });
        } catch (Exception $exception) {
            throw $exception;
        }

    }

    public function getOrders(array $requestData): array
    {
        try {
            $orders = $this->orderRepository->getFilteredOrders($requestData);

            return [
                'orders' => OrderResource::collection($orders),
                'page_info' => $this->getPaginateInfo($orders),
            ];
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getOrderById(int $id): OrderResource
    {
        try {
            return new OrderResource($this->orderRepository->findWithItems($id));
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function cancelOrder(int $id): OrderResource
    {
        return DB::transaction(function () use ($id) {

            $order = $this->orderRepository->findWithItems($id);

            if ($order->status->key == 'cancelled') {
                throw new Exception(Constants::$ERROR_MESSAGE['order_already_cancelled'],
                    Constants::$ERROR_CODE['unprocessable_entity']);
            }

            foreach ($order->items as $item) {
                $this->productRepository->incrementStock($item->product_id, $item->quantity);
            }

            $this->orderRepository->updateStatus($id, 3);

            return new OrderResource($this->orderRepository->findWithItems($id));
        });
    }
}
