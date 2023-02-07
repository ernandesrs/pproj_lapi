<?php

namespace App\Http\Controllers\Dash;

use App\Exceptions\Dash\HasActiveSubscriptionException;
use App\Exceptions\Dash\PaymentFailException;
use App\Exceptions\NotFoundException;
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
        if (
            $request->user()->subscriptions()
                ->where("ends_in", ">=", now())
                ->where("status", "!=", Subscription::STATUS_ENDED)
                ->where("status", "!=", Subscription::STATUS_CANCELED)->count()
        ) {
            throw new HasActiveSubscriptionException();
        }

        $price = 10000;
        $data = $request->validated();

        $creditCard = $request->user()->creditCards()->where("id", $data["card_id"])->first();

        // faça uma cobrança no cartão
        $response = (new PagarMe())->createTransaction($creditCard, ($price * $data["period"]), $data["installments"]);

        $subscription = \Auth::user()->subscriptions()->create([
            "starts_in" => now(),
            "ends_in" => now()->addMonth($data["period"]),
            "type" => Subscription::TYPE_NEW,
            "status" => in_array($response["status"], ["processing", "waiting_payment"]) ? Subscription::STATUS_PENDING : Subscription::STATUS_ACTIVE
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