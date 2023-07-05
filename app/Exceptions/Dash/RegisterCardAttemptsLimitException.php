<?php

namespace App\Exceptions\Dash;

use Exception;

class RegisterCardAttemptsLimitException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Number of card registration attempts reached";

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            "success" => false,
            "error" => class_basename($this),
            "message" => $this->message
        ], 429);
    }
}