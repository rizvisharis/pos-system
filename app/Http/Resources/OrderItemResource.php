<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => $this->whenLoaded('product', fn () => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
            ]),
            'product_snapshot' => [
                'name' => $this->product_name,
                'price' => $this->unit_price,
            ],
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
        ];
    }
}
