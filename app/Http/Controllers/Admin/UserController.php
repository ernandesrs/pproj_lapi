<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\Admin\NotHaveAdminPanelAcessException;
use App\Exceptions\Admin\UnauthorizedActionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use TraitFilter;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->filter($request, new User())->orderBy("level", "desc");

        return response()->json([
            "success" => true,
            "users" => UserResource::collection($users->paginate($this->limit))
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

        $user = (new UserService())->register($request->validated());

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
        $this->authorize("delete", $user);

        (new UserService())->delete($user);

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * Photo delete
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function photoDelete(User $user)
    {
        $this->authorize("update", $user);

        (new UserService)->photoDelete($user);

        return response()->json([
            "success" => true
        ]);
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

    /**
     * Promote user to next level
     *
     * @param User $user
     * @return void
     */
    public function promote(User $user)
    {
        /**
         * @var User
         */
        $logged = Auth::user();

        if (!$logged->isSuperadmin()) {
            throw new UnauthorizedActionException();
        }

        switch ($user->level) {
            case User::LEVEL_COMMON:
                $user->level = User::LEVEL_ADMIN;
                break;

            case User::LEVEL_ADMIN:
                $user->level = User::LEVEL_SUPER;
                break;
        }

        $user->save();

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
        ]);
    }

    /**
     * Demote user to previous level
     *
     * @param User $user
     * @return void
     */
    public function demote(User $user)
    {
        /**
         * @var User
         */
        $logged = Auth::user();

        if (!$logged->isSuperadmin()) {
            throw new UnauthorizedActionException();
        }

        switch ($user->level) {
            case User::LEVEL_SUPER:
                $user->level = User::LEVEL_ADMIN;
                break;

            case User::LEVEL_ADMIN:
                $user->level = User::LEVEL_COMMON;
                break;
        }

        $user->save();

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
        ]);
    }
}
