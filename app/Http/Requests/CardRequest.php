<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
{
    use TraitApiRequest;

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
            "name" => ["nullable", "string", "max:50"],
            "number" => [
                "required",
                "numeric",
                "digits:16",
                function ($attr, $val, $fail) {
                    $last = substr($val, 12, 4);

                    $paymentMethods = $this->user()->paymentMethods()->firstOrCreate();
                    if ($paymentMethods->cards()->where("last_digits", $last)->count()) {
                        $fail("Cartão já cadastrado");
                        return;
                    }
                }
            ],
            "holder_name" => ["required"],
            "expiration_date" => ["required", "numeric", "digits:4"],
            "cvv" => ["required", "numeric", "digits:3"],
        ];
    }
}