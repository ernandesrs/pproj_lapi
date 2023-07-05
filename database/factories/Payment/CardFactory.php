<?php

namespace Database\Factories\Payment;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cardNumber = $this->faker->creditCardNumber();
        return [
            "name" => "Cartão " . ['Nubanko', 'Dubanko', 'Merkado Bom', 'Brasuka', 'Grotesko', 'Itaí'][rand(0, 5)],
            "brand" => $this->faker->creditCardType(),
            "hash" => md5(uniqid()),
            "holder_name" => $this->faker->firstName() . " " . $this->faker->lastName(),
            "last_digits" => substr($cardNumber, strlen($cardNumber) - 4),
            "expiration_date" => str_replace("/", "", $this->faker->creditCardExpirationDateString()),
        ];
    }
}