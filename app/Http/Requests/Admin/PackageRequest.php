<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\TraitApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            "name" => ["required", "string", "max:75", "min:3", "unique:packages,name" . ($this->package?->id ? "," . $this->package->id : "")],
            "description" => ["nullable", "string"],
            "price" => ["required", "numeric"],
            "expiration month" => ["required", "numeric", "min:1"],
            "show" => ["required", "boolean"]
        ];
    }
}