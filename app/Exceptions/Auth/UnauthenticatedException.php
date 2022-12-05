<?php

namespace App\Exceptions\Auth;

use Exception;

class UnauthenticatedException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Unauthenticated.";

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
