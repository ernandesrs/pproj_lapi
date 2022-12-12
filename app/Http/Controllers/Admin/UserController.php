<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserRegistered;
use App\Exceptions\Admin\NotHaveAdminPanelAcessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            "success" => true,
            "users" => UserResource::collection(User::whereNotNull("id")->paginate(10))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize("create", new User());

        $validated = $request->validated();

        $user = (new UserService())->register($validated);

        event(new UserRegistered($user));

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize("view", $user);

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize("update", $user);

        $user = (new UserService())->update($user, $request->validated());

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * User permission update
     *
     * @param User $user
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function permissionUpdate(User $user, Permission $permission)
    {
        if (!in_array($user->level, [User::LEVEL_ADMIN, User::LEVEL_SUPER])) {
            throw new NotHaveAdminPanelAcessException();
        }

        $user->permission_id = $permission->id;
        $user->save();

        return response()->json([
            "success" => true
        ]);
    }
}
