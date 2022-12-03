<?php

namespace App\Exceptions\Account;

use Exception;

class LoginFailException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Email or password is invalid.";

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            "success" => false,
            "error" => class_basename($this),
            "message" => $this->message
        ], 401);
    }
}
