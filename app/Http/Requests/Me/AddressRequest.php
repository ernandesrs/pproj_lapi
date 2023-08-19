<?php

namespace App\Http\Requests\Me;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => ["required", "string", "max:15"],
            "street" => ["required", "string", "max:75"],
            "number" => ["required", "numeric"],
            "zipcode" => ["required", "string", "max:20"],
            "country" => ["required", "string", "max:2"],
            "state" => ["required", "string", "max:2"],
            "city" => ["required", "string", "max:50"],
            "neighborhood" => ["required", "string", "max:50"],
            "complementary" => ["nullable", "max:100"]
        ];
    }
}