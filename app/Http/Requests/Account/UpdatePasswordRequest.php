<?php

namespace App\Http\Requests\Account;

use App\Exceptions\Account\UpdatePasswordTokenInvalidException;
use App\Http\Requests\TraitApiRequest;
use App\Models\PasswordReset;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            "token" => [
                function ($attr, $val, $fail) {
                    if (PasswordReset::where("token", $val ?? "")->count() === 0)
                        throw new UpdatePasswordTokenInvalidException();
                }
            ],
            "password" => ["required", "min:6", "max:12", "confirmed"]
        ];
    }

    /**
     * Messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            "exists" => ["Password recovery token is invalid"]
        ];
    }
}