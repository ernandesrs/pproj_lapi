<?php

namespace App\Exceptions\Dash\Payments;

use Exception;

class InvalidCardException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "The provided credit card is invalid";

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