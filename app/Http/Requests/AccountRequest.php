<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            "first_name" => ["required", "max:50"],
            "last_name" => ["required", "max:100"],
            "username" => ["required", "max:50", "unique:users,username"],
            "gender" => ["required", Rule::in(["n", "m", "f"])],
            "email" => ["required", "email", "unique:users,email"]
        ];

        if ($this->user) {
            $rules["password"] = ["nullable", "confirmed", "min:6", "max:12"];
        } else {
            $rules["password"] = ["required", "confirmed", "min:6", "max:12"];
        }

        return $rules;
    }
}
