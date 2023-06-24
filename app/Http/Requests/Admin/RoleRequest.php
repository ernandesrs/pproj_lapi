<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\TraitApiRequest;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            "display_name" => ["required", "max:25", "unique:roles,display_name" . ($this->role?->id ? "," . $this->role->id : "")],
            "permissibles.*" => [
                $this->role?->id ? "required" : "nullable",
                function ($attr, $val, $fail) {
                    $permissible = str_replace("_", '\\', explode(".", $attr)[1] ?? "");

                    if (!key_exists($permissible, Role::PERMISSIBLES)) {
                        $fail("This permissible is inválid.");
                        return;
                    }
                }
            ],
            "permissibles.*.*" => [
                $this->role?->id ? "required" : "nullable",
                function ($attr, $val, $fail) {
                    $permissible = str_replace("_", '\\', explode(".", $attr)[1] ?? "");
                    $permission = explode(".", $attr)[2] ?? "";

                    if (!key_exists($permission, Role::PERMISSIBLES[$permissible] ?? [])) {
                        $fail("This permission is inválid.");
                        return;
                    }

                    if (!is_bool($val)) {
                        $fail("Only boolean values has accept.");
                        return;
                    }
                }
            ]
        ];
    }
}