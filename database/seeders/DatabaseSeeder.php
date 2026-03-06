<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            OrderStatusSeeder::class,
        ]);

        Shop::factory(5)->create()->each(function ($shop) {

            $products = Product::factory(10)->create([
                'shop_id' => $shop->id,
            ]);

            Order::factory(20)->create([
                'shop_id' => $shop->id,
                'status_id' => 1,
            ])->each(function ($order) use ($products) {

                $items = $products->random(rand(1, 3));

                $total = 0;

                foreach ($items as $product) {

                    $qty = rand(1, 3);
                    $subtotal = $qty * $product->price;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                    ]);

                    $total += $subtotal;
                }

                $order->update([
                    'total_amount' => $total,
                ]);
            });
        });
    }
}
