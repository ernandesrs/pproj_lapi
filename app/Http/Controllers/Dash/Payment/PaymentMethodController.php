<?php

namespace App\Http\Controllers\Dash\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dash\Payment\PaymentMethodRequest;
use App\Http\Resources\Payment\PaymentMethodResource;

class PaymentMethodController extends Controller
{
    /**
     * Show payment methods
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            "success" => true,
            "payment_methods" => new PaymentMethodResource(\Auth::user()->paymentMethods()->firstOrCreate())
        ]);
    }

    /**
     * Update payment methods
     *
     * @param \App\Http\Requests\Dash\Payment\PaymentMethodRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PaymentMethodRequest $request)
    {
        $paym = \Auth::user()->paymentMethods()->firstOrFail();
        $paym->update(
            $request->validated()
        );

        return response()->json([
            "success" => true,
            "payment_method" => new PaymentMethodResource($paym)
        ]);
    }
}