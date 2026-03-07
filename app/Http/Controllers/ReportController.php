<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\GetTopProductsRequest;
use App\Services\ReportService;
use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    use ResponseTraits;

    public function topProducts(GetTopProductsRequest $request, ReportService $reportService)
    {
        try {
            $report = $reportService->topProducts($request->validated());

            return $this->successResponse($report, 'Top products retrieved successfully');
        } catch (ValidationException $validationException) {
            return $this->validationErrorResponse($validationException);
        } catch (Exception $exception) {
            return $this->exceptionErrorResponse($exception);
        }
    }
}
