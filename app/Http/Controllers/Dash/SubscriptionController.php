<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            "success" => true,
            "subscriptions" => \Auth::user()->subscriptions()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriptionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SubscriptionRequest $request)
    {
        $period = $request->get("period", 1);
        $installments = $request->get("installments", 1);

        $creditCard = $request->user()->creditCards()->where("id", $request->get("card_id"))->first();
        if (!$creditCard) {
            return response()->json([
                "success" => false,
                "message" => "Credit card not found.",
            ]);
        }

        // faça uma cobrança no cartão
        // ...

        $subscription = \Auth::user()->subscriptions()->create([
            "starts_in" => now(),
            "ends_in" => now()->addMonth($period),
            "type" => Subscription::TYPE_NEW,
            "status" => Subscription::STATUS_ACTIVE
        ]);

        return response()->json([
            "success" => true,
            "subscription" => $subscription
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $subscription = \Auth::user()->subscriptions()->where("id", $id)->first();

        return response()->json([
            "success" => true,
            "subscription" => $subscription
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()->json([]);
    }
}