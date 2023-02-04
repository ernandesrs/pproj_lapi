<?php

namespace App\Http\Requests\Account;

use App\Exceptions\Account\VerificationTokenInvalidException;
use App\Http\Requests\TraitApiRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'token' => filter_input(INPUT_GET, 'token', FILTER_DEFAULT) ?? null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (empty($this->token)) {
            throw new VerificationTokenInvalidException();
        }

        return [
            "token" => [
                function ($attr, $val, $fail) {
                    if (User::where("verification_token", $val ?? "")->count() === 0) {
                        throw new VerificationTokenInvalidException();
                    }
                }
            ]
        ];
    }
}