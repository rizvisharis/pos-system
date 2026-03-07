<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use Tests\TestCase;

class OrderConcurrencyTest extends TestCase
{
    public function test_stock_cannot_go_negative_when_two_orders_are_placed()
    {
        $shop = Shop::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'price' => 100,
            'stock' => 1,
        ]);

        $payload = [
            'shop_id' => $shop->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 1,
                ],
            ],
        ];

        $response1 = $this->postJson('/api/orders', $payload);
        $response1->assertStatus(201);

        $response2 = $this->postJson('/api/orders', $payload);

        $response2->assertStatus(422);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 0,
        ]);
    }
}
