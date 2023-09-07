<?php

namespace App\Exceptions;

use Exception;

class AppDemoException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Feature disabled when application is in demo mode.";

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
        ], 503);
    }
}