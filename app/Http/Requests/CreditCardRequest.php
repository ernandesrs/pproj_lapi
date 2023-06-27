<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardRequest extends FormRequest
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
            "card_number" => [
                "required",
                "numeric",
                "digits:16",
                function ($attr, $val, $fail) {
                    $last = substr($val, 12, 4);

                    if ($this->user()->creditCards()->where("last_digits", $last)->count()) {
                        $fail("Cartão já cadastrado");
                        return;
                    }
                }
            ],
            "card_holder_name" => ["required"],
            "card_expiration_date" => ["required", "numeric", "digits:4"],
            "card_cvv" => ["required", "numeric", "digits:3"],
        ];
    }
}