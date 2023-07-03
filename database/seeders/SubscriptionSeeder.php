<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where("id", "<", 4)->get()->each(function ($user) {
            $user->subscriptions()->saveMany(
                Subscription::factory([18, 12, 26][rand(0, 2)])->make()
            );
        });
    }
}