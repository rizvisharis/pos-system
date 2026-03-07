<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ReportRepositoryInterface
{
    public function topProducts(array $requestData): Collection;
}
