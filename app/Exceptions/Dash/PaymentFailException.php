<?php

namespace App\Exceptions\Dash;

use Exception;

class PaymentFailException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Unable to make payment";

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