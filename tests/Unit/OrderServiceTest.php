<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\OrderService;
use Mockery;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    public function test_create_order_calculates_total_and_updates_stock()
    {
        $orderRepo = Mockery::mock(OrderRepositoryInterface::class);
        $productRepo = Mockery::mock(ProductRepositoryInterface::class);

        $service = new OrderService($orderRepo, $productRepo);

        $product = new Product([
            'name' => 'Laptop',
            'price' => 100,
            'stock' => 10,
        ]);
        $product->id = 500;

        $order = new Order;
        $order->id = 1000;

        $data = [
            'shop_id' => 1,
            'items' => [
                [
                    'product_id' => 500,
                    'qty' => 2,
                ],
            ],
        ];

        $orderRepo->shouldReceive('create')
            ->once()
            ->andReturn($order);

        $productRepo->shouldReceive('lockProduct')
            ->once()
            ->with(500)
            ->andReturn($product);

        $orderRepo->shouldReceive('addItem')->once();

        $productRepo->shouldReceive('decrementStock')
            ->once()
            ->with(500, 2);

        $orderRepo->shouldReceive('updateTotal')
            ->once()
            ->with(1000, 200);

        $orderRepo->shouldReceive('findWithItems')
            ->once()
            ->andReturn($order);

        $result = $service->createOrder($data);

        $this->assertNotNull($result);
    }
}
