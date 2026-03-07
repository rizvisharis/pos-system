<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function lockProduct($id): Product;

    public function incrementStock($id, $qty): int;

    public function decrementStock($id, $qty): int;
}
