<?php

namespace App\Exceptions\Account;

use Exception;

class VerificationTokenInvalidException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Verification token is invalid.";

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
