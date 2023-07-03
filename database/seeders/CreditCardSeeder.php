<?php

namespace Database\Seeders;

use App\Models\CreditCard;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where("id", "<", 4)->get()->each(function ($user) {
            $user->creditCards()->saveMany(
                CreditCard::factory([5, 7, 9][rand(0, 2)])->make([
                    "holder_name" => $user->first_name . " " . $user->last_name
                ])
            );
        });
    }
}