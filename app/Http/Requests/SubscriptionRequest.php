<?php

namespace App\Http\Requests;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
            "card_id" => [
                "required",
                "numeric",
                function ($attr, $val, $fail) {
                    $card = \Auth::user()->cards()->where("id", $val)->count();
                    if ($card === 0) {
                        $fail("Cartão de crédito inválido ou inexistente.");
                        return;
                    }
                }
            ],
            "package_id" => [
                "required",
                "numeric",
                function ($attr, $val, $fail) {
                    if (!Package::where("id", $val)->count()) {
                        $fail("Pacote não encontrado.");
                        return;
                    }
                }
            ],
            "installments" => ["required", "numeric", "min:1", "max:12"]
        ];
    }
}