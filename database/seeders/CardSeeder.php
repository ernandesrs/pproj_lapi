<?php

namespace Database\Seeders;

use App\Models\Payment\Card;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where("id", "<", 4)->get()->each(function ($user) {
            $user->paymentMethods()->firstOrCreate()->cards()->saveMany(
                Card::factory([1, 2, 3][rand(0, 2)])->make([
                    "holder_name" => $user->first_name . " " . $user->last_name
                ])
            );
        });
    }
}