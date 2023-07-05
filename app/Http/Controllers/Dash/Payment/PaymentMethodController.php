<?php

namespace App\Http\Controllers\Dash\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Show payment methods
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $paym = \Auth::user()->paymentMethods()->first();
        if (!$paym) {
            \Auth::user()->paymentMethods()->create();
        }

        return response()->json([
            "success" => true,
            "payment_methods" => $paym
        ]);
    }
}