<?php

namespace App\Http\Controllers\Dash\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dash\Payment\PaymentMethodRequest;
use App\Models\Payment\PaymentMethod;
use Illuminate\Http\Request;

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
            "payment_methods" => \Auth::user()->paymentMethods()->firstOrCreate()
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
            "payment_method" => $paym
        ]);
    }
}