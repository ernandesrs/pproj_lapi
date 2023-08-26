<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\MePhotoUploadRequest;
use App\Http\Requests\Account\MeUpdateRequest;
use App\Http\Requests\Account\RecoveryRequest;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
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
     * Get the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            "success" => true,
            "user" => new UserResource(Auth::user())
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
        $user = $this->userService->update(Auth::user(), $request->validated());

        return response()->json([
            "success" => true,
            "user" => new UserResource($user),
        ]);
    }

    /**
     * Update the authenticated user email
     *
     * @param MeUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestEmailUpdate(UserUpdateEmailRequest $request)
    {
        $this->userService->requestEmailUpdate(Auth::user(), $request->validated());
        return response()->json([
            "success" => true
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

        $user = $this->userService->photoDelete(Auth::user());

        $user->update([
            "photo" => $validated["photo"]->store("users/profile", "public")
        ]);

        return response()->json([
            "success" => true,
            "user" => new UserResource($user),
        ]);
    }

    /**
     * Photo delete
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function photoDelete()
    {
        $this->userService->photoDelete(Auth::user());

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * Me delete
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        $this->userService->remove(Auth::user());

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * Deleted account recovery
     *
     * @param RecoveryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recovery(RecoveryRequest $request)
    {
        $validated = $request->validated();

        $user = User::where("email", $validated["email"])->first();

        $this->userService->recovery($user);

        return response()->json([
            "success" => true
        ]);
    }
}