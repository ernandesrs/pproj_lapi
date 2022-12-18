<?php

namespace App\Exceptions\Account;

use Exception;

class UpdatePasswordTokenInvalidException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "The update password token is invalid.";

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            "success" => false,
            "error" => class_basename($this),
            "message" => $this->message
        ], 422);
    }
}
