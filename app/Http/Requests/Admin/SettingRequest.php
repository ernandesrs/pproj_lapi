<?php

namespace App\Http\Requests\Admin;

use App\Exceptions\InvalidDataException;
use App\Http\Requests\TraitApiRequest;
use App\Models\Admin\Setting;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        $name = $this->name;
        if ($this?->id) {
            $name = Setting::where("id", $this->id)->firstOrFail()->name;
        }

        $rules["app_name"] = ["required"];

        return array_merge($rules, $this->getSettingModelInstance($name)->rules());
    }

    /**
     * Get the validation messages
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return array_merge([
            "app_name.required" => "Informe um nome",
        ], $this->getSettingModelInstance($this->name)->rulesMessages());
    }

    /**
     * Get Setting Model Instance Or Fail
     *
     * @param mixed $name
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getSettingModelInstance($to)
    {
        $settingModel = "\\App\\Models\\Admin\\" . $to;
        if (!class_exists($settingModel) || is_null($this)) {
            throw new InvalidDataException();
        }
        return new $settingModel;
    }
}