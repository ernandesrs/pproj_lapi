<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            "first_name" => ["required", "max:50"],
            "last_name" => ["required", "max:100"],
            "username" => ["required", "max:50", "unique:users,username" . ($this->user ? "," . $this->user->id : "")],
            "gender" => ["required", Rule::in(["n", "m", "f"])],
            "email" => ["required", "email", "unique:users,email" . ($this->user ? "," . $this->user->id : "")],
            "password" => [($this->user ? "nullable" : "required"), "confirmed", "min:6", "max:12"]
        ];

        return $rules;
    }
}
