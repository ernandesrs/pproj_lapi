<?php

namespace App\Http\Controllers\Auth;

use App\Events\ForgetPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\ForgetRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{

    /**
     * Foget password
     *
     * @param ForgetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(ForgetRequest $request)
    {
        $validated = $request->validated();
        $user = User::where("email", $validated["email"])->firstOrFail();

        $token = Str::random(60);
        PasswordReset::create([
            "email" => $user->email,
            "token" => $token
        ]);

        event(new ForgetPassword($user, $token));

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * Update password
     *
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();

        $resetPassword = PasswordReset::where("token", $validated["token"])->first();

        $user = User::where("email", $resetPassword->email)->firstOrFail();
        $user->update([
            "password" => Hash::make($validated["password"])
        ]);

        PasswordReset::where("email", $user->email)->delete();

        return response()->json([
            "success" => true
        ]);
    }
}