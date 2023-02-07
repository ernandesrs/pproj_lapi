<?php

namespace App\Exceptions\Dash\Pagarme;

use Exception;

class RefusedPaymentException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Refused";

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