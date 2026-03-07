<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use Exception;

class ReportService
{
    public function __construct(private ReportRepositoryInterface $reportRepository) {}

    public function topProducts(array $requestData)
    {
        try {
            return $this->reportRepository->topProducts($requestData);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
