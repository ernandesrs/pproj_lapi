<?php

namespace App\Exceptions\Admin;

use Exception;

class UnauthorizedActionException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "You not have permission for this action.";

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
