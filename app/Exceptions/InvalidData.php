<?php

namespace App\Exceptions;

use Exception;

class InvalidData extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Invalid data has found";

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            "success" => false,
            "error" => class_basename($this),
            "message" => $this->message,
            "errors" => session()->get("validator_errors", null),
        ], 422);
    }
}
