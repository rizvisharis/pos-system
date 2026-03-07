<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'shop' => $this->whenLoaded('shop', fn () => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
                'location' => $this->shop->location,
            ]),
            'status' => $this->whenLoaded('status', fn () => $this->status->value,
            ),
            'total_amount' => $this->total_amount,
            'placed_at' => $this->placed_at,
            'items' => OrderItemResource::collection($this->items),
        ];
    }
}
