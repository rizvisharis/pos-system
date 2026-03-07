<?php

namespace App\Repositories;

use App\Models\OrderItem;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public $model;

    public function __construct(OrderItem $model)
    {
        $this->model = $model;
    }

    public function topProducts(array $requestData): Collection
    {
        return $this->model->select(
            'product_id',
            'product_name',
            DB::raw('SUM(quantity) as total_sold')
        )
            ->whereHas('order', function ($q) use ($requestData) {

                $q->where('shop_id', $requestData['shop_id'])
                    ->whereDate('placed_at', '>=', $requestData['from'])
                    ->whereDate('placed_at', '<=', $requestData['to']);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }
}
