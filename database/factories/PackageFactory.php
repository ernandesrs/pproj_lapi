<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $em = rand(1, 12);
        return [
            'name' => $this->faker->text(15),
            'description' => $this->faker->text(50),
            'price' => 12 * $em,
            'expiration_month' => $em,
            'show' => true
        ];
    }
}