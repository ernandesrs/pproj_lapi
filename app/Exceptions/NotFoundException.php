<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Requested resource not found.";

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'success' => false,
            'error' => class_basename($this),
            'message' => $this->message,
        ], 404);
    }
}
