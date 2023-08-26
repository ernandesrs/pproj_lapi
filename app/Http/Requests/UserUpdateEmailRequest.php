<?php

namespace App\Http\Requests;

use App\Models\UserEmailUpdate;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateEmailRequest extends FormRequest
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
            "new_email" => [
                "required",
                "email",
                "unique:users,email",
                function ($attr, $val, $fail) {
                    if (UserEmailUpdate::where("created_at", ">=", \Illuminate\Support\Carbon::now()->subDays(1))->count()) {
                        $fail("Você já solicitou atualização hoje, acesse o e-mail e confirme.");
                        return;
                    }
                }
            ],
        ];
    }
}