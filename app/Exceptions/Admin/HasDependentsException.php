<?php

namespace App\Exceptions\Admin;

use Exception;

class HasDependentsException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "This resource has another resource that depends on it.";

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
