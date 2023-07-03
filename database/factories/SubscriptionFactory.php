<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startsIn = new Carbon(time());
        $pack = Package::where("id", "=", rand(1, Package::all()->count()))->first();
        return [
            'package_id' => $pack->id,
            'package_metadata' => $pack->toJson(),
            'gateway' => ['pagarme', 'mercadopago'][rand(0, 1)],
            'transaction_id' => uniqid(),
            'status' => [
                Subscription::STATUS_ENDED,
                Subscription::STATUS_PENDING,
                Subscription::STATUS_CANCELED
            ][rand(0, count(Subscription::STATUS) - 2)],
            'starts_in' => $startsIn,
            'ends_in' => $startsIn->addMonths($pack->expiration_month)
        ];
    }
}