<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\Admin\NotHaveAdminPanelAcessException;
use App\Exceptions\Admin\UnauthorizedActionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);

        $users = $this->filter($request, new User())->paginate($this->limit)->withQueryString();

        return response()->json([
            "success" => true,
            "data" => UserResource::collection($users)->response()->getData()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
     * User roles update
     *
     * @param User $user
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function roleUpdate(User $user, Role $role)
    {
        $this->authorize("update", $user);

        if (!in_array($user->level, [User::LEVEL_ADMIN, User::LEVEL_SUPER])) {
            throw new NotHaveAdminPanelAcessException();
        }

        if (!$user->roles()->where('id', $role->id)->count())
            $user->roles()->attach($role->id);

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * User roles delete
     *
     * @param User $user
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function roleDelete(User $user, Role $role)
    {
        $this->authorize("update", $user);

        if ($user->roles()->where('id', $role->id)->count())
            $user->roles()->detach($role->id);

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
        $this->authorize("update", $user);

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
        $this->authorize("update", $user);

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
