<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\TraitApiRequest;
use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
        // 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["required", "max:50", "unique:permissions,name" . ($this->permission ? "," . $this->permission->id : "")],
            "list" => [function ($attr, $rulablesList, $fail) {
                foreach ($rulablesList as $rulable => $rulableActions) {
                    // rullable validate
                    if (!key_exists($rulable, Permission::RULABLES)) {
                        $fail("Gerenciável não existe.");
                        return;
                    }

                    // actions validate
                    foreach ($rulableActions as $actionName => $actionActive) {
                        if (!in_array($actionName, Permission::RULABLES_ACTIONS)) {
                            $fail("Tipo de ação para o gerenciável não existe.");
                            return;
                        }
                    }
                }
            }]
        ];
    }
}
