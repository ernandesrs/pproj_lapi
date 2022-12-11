<?php

namespace App\Exceptions\Admin;

use Exception;

class NotHaveAdminPanelAcessException extends Exception
{
    /**
     * Message
     *
     * @var string
     */
    protected $message = "Only administrator users may have permissions rules.";

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
