<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        foreach (Permission::RULABLES as $key => $item) {
            $rules[$key] = [];
        }

        return [
            'name' => 'Permision ' . uniqid(),
            'list' => json_encode($rules)
        ];
    }
}
