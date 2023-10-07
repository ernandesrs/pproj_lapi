<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\VerifyRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    /**
     * Account verifiy
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyAccount(VerifyRequest $request)
    {
        $user = \Auth::user();

        $user->verification_token = null;
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            "success" => true,
            "message" => "Your account has been verified."
        ]);
    }

    /**
     * Resend verification link
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendVerification()
    {
        /**
         * @var \App\Models\User
         */
        $user = \Auth::user();

        if ($user->email_verified_at) {
            return response()->json([
                "success" => false,
                "message" => "Your account has already been verified."
            ]);
        }

        if (!$user->verification_token) {
            $user->update([
                "verification_token" => Str::random(50)
            ]);
        }

        event(new UserRegistered($user));

        return response()->json([
            "success" => true,
            "message" => "A new verification link has been sent."
        ]);
    }
}