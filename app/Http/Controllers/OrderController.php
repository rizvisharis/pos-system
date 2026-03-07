<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\IndexRequest;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Services\OrderService;
use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    use ResponseTraits;

    public function store(StoreOrderRequest $request, OrderService $orderService)
    {
        try {
            // 201
            $order = $orderService->createOrder($request->validated());

            return $this->successResponse($order, 'Order created successfully', 201);
        } catch (ValidationException $validationException) {
            return $this->validationErrorResponse($validationException);
        } catch (Exception $exception) {
            return $this->exceptionErrorResponse($exception);
        }
    }

    public function index(IndexRequest $request, OrderService $orderService)
    {
        try {
            // $request->validated()
            $orders = $orderService->getOrders($request->validated());

            return $this->successResponse($orders, 'Orders retrieved successfully');
        } catch (ValidationException $validationException) {
            return $this->validationErrorResponse($validationException);
        } catch (Exception $exception) {
            return $this->exceptionErrorResponse($exception);
        }
    }

    public function show(int $id, OrderService $orderService)
    {
        try {
            $order = $orderService->getOrderById($id);

            return $this->successResponse($order, 'Order retrieved successfully');
        } catch (ValidationException $validationException) {
            return $this->validationErrorResponse($validationException);
        } catch (Exception $exception) {
            return $this->exceptionErrorResponse($exception);
        }
    }

    public function cancel(int $id, OrderService $orderService)
    {
        try {
            $cancelOrder = $orderService->cancelOrder($id);

            return $this->successResponse($cancelOrder, 'Order cancelled successfully');
        } catch (ValidationException $validationException) {
            return $this->validationErrorResponse($validationException);
        } catch (Exception $exception) {
            return $this->exceptionErrorResponse($exception);
        }
    }
}
