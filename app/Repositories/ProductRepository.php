<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function lockProduct($id): Product
    {
        return $this->model->where('id', $id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function decrementStock($id, $qty): int
    {
        return $this->model->where('id', $id)->decrement('stock', $qty);
    }

    public function incrementStock($id, $qty): int
    {
        return $this->model->where('id', $id)->increment('stock', $qty);
    }
}
