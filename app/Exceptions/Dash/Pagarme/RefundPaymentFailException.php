<?php

namespace App\Exceptions\Dash\Pagarme;

use Exception;

class RefundPaymentFailException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Refund fail";

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