<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\MePhotoUploadRequest;
use App\Http\Requests\Account\MeUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MeController extends Controller
{
    /**
     * Get the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            "success" => true,
            "user" => Auth::user()
        ]);
    }

    /**
     * Update the authenticated user
     *
     * @param MeUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(MeUpdateRequest $request)
    {
        /**
         * @var User
         */
        $user = Auth::user();

        $validated = $request->validated();
        if ($validated["password"] ?? null)
            $validated["password"] = Hash::make($validated["password"]);

        $user->update(
            $validated
        );

        return response()->json([
            "success" => true,
            "user" => $user,
        ]);
    }

    /**
     * Photo upload
     *
     * @param MePhotoUploadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function photoUpload(MePhotoUploadRequest $request)
    {
        $validated = $request->validated();

        /**
         * @var User
         */
        $user = Auth::user();
        if ($user->photo) {
            Storage::delete("public/" . $user->photo);
        }

        $user->update([
            "photo" => $validated["photo"]->store("users/profile", "public")
        ]);

        return response()->json([
            "success" => true,
            "photo" => $user->photo,
        ]);
    }

    /**
     * Photo delete
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function photoDelete()
    {
        /**
         * @var User
         */
        $user = Auth::user();

        if ($user->photo) {
            Storage::delete("public/" . $user->photo);

            $user->update([
                "photo" => null
            ]);
        }

        return response()->json([
            "success" => true
        ]);
    }
}
