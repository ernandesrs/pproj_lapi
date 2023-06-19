<?php

namespace App\Http\Controllers\Dash;

use App\Exceptions\Dash\HasActiveSubscriptionException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Package;
use App\Models\Subscription;
use App\Services\Payments\Pagarme;
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

        $data = $request->validated();

        $package = Package::where("id", $data["package_id"])->first();
        $creditCard = $request->user()->creditCards()->where("id", $data["card_id"])->first();
        $response = (new Pagarme())->createTransaction(
            $creditCard,
            $package->price,
            $data["installments"],
            $package->toArray()
        );

        $subscription = \Auth::user()->subscriptions()->create([
            "package_id" => $package->id,
            "package_metadata" => $package->toJson(),
            "transaction_id" => $response["transaction_id"],
            "gateway" => $response["gateway"],
            "starts_in" => now(),
            "ends_in" => now()->addMonths($package->expiration_month),
            "type" => Subscription::TYPE_NEW,
            "status" => in_array($response["status"], ["processing", "waiting_payment"]) ? Subscription::STATUS_PENDING : Subscription::STATUS_ACTIVE
        ]);

        $subscription->package_metadata = json_decode($subscription->package_metadata);

        return response()->json([
            "success" => true,
            "subscription" => $subscription,
            "package" => $package
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
     * Cancel subscription
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        $subscription = \Auth::user()->subscriptions()->where("id", $id)->first();

        if (!$subscription || in_array($subscription->status, [Subscription::STATUS_CANCELED, Subscription::STATUS_ENDED])) {
            throw new NotFoundException();
        }

        $response = (new Pagarme())->fullRefund($subscription->transaction_id);

        $subscription->cancel($response["transaction_id"]);
        $subscription->package_metadata = json_decode($subscription->package_metadata);

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