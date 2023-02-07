<?php

namespace App\Exceptions\Dash;

use Exception;

class HasActiveSubscriptionException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "You have a valid subscription";

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