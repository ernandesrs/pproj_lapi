<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize("viewAny", Subscription::class);

        return response()->json([
            "success" => true,
            "subscriptions" => (new Subscription())->all()->map(function ($subscription) {
                $subscription->user = $subscription->user()->first();
                return $subscription;
            })
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Subscription $subscription)
    {
        $this->authorize("view", Subscription::class);

        $subscription->user = $subscription->user()->first();
        return response()->json([
            "success" => true,
            "subscription" => $subscription
        ]);
    }
}