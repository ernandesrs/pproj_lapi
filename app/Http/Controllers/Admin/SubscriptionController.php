<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Services\FilterService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", Subscription::class);

        $subscriptions = (new FilterService(new Subscription))->filter($request);

        return response()->json([
            "success" => true,
            "subscriptions" => SubscriptionResource::collection($subscriptions->withQueryString())
                ->response()->getData()
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