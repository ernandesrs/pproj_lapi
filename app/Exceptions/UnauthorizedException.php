<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Access not authorized.";

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            "success" => false,
            "error" => class_basename($this),
            "message" => $this->message
        ], 403);
    }
}
