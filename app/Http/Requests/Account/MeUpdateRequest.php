<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\TraitApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MeUpdateRequest extends FormRequest
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
        return [
            "first_name" => ["required", "max:50"],
            "last_name" => ["required", "max:100"],
            "username" => ["required", "max:50", "unique:users,username," . Auth::user()->id],
            "gender" => ["required", Rule::in(["n", "m", "f"])],
            "password" => ["nullable", "confirmed", "min:6", "max:12"]
        ];
    }
}
