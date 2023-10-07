<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Services\UserService;

class EmailUpdateController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * User email update request link
     *
     * @param UserUpdateEmailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestEmailUpdate(UserUpdateEmailRequest $request)
    {
        $this->userService->requestEmailUpdate(\Auth::user(), $request->validated());
        return response()->json([
            "success" => true
        ]);
    }

    /**
     * User email update
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function emailUpdate(string $token)
    {
        $this->userService->emailUpdate(\Auth::user(), $token);
        return response()->json([
            "success" => true
        ]);
    }
}