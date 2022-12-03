<?php

namespace App\Http\Requests;

use App\Exceptions\InvalidData;

trait TraitApiRequest
{
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->count()) {
                session()->flash("validator_errors", $validator->errors());
                throw new InvalidData();
            }
        });
    }
}
