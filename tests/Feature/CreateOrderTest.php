<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    public function test_order_can_be_created()
    {
        $shop = Shop::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'price' => 100,
            'stock' => 10,
        ]);

        $payload = [
            'shop_id' => $shop->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 2,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'total_amount' => 200,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);
    }
}
