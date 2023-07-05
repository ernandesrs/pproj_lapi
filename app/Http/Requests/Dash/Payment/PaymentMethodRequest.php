<?php

namespace App\Http\Requests\Dash\Payment;

use App\Http\Requests\TraitApiRequest;
use App\Models\Payment\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends FormRequest
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
            "method_preferred" => ["required", Rule::in(PaymentMethod::PAY_METHODS)],
            "preferred_card_id" => [
                "nullable",
                "numeric",
                function ($attr, $val, $fail) {
                    if (
                        !$this->user()
                            ->paymentMethods()->firstOrFail()
                            ->cards()->where("id", $val)->count()
                    ) {
                        $fail("Cartão não encontrado");
                        return;
                    }
                }
            ]
        ];
    }
}