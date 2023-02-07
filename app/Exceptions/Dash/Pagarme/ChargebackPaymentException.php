<?php

namespace App\Exceptions\Dash\Pagarme;

use Exception;

class ChargebackPaymentException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Chargeback";

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