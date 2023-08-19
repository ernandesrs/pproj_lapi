<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\Admin\NotHaveAdminPanelAcessException;
use App\Exceptions\Admin\UnauthorizedActionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserLevelRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\FilterService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Filter Service
     * @var FilterService
     */
    private $filterService;

    /**
     * User Service
     * @var UserService
     */
    private $userService;

    public function __construct()
    {
        $this->filterService = new FilterService(new User());
        $this->userService = new UserService();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);

        $users = $this->filterService->filter($request);

        return response()->json([
            "success" => true,
            "users" => UserResource::collection($users->withQueryString())
                ->response()->getData()
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

        $user = $this->userService->register($request->validated());

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

        $user = $this->userService->update($user, $request->validated());

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

        $this->userService->delete($user);

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
        $this->authorize("updateRole", $user);

        if (!in_array($user->level, [User::LEVEL_ADMIN, User::LEVEL_SUPER])) {
            throw new NotHaveAdminPanelAcessException();
        }

        if (!$user->roles()->where('id', $role->id)->count())
            $user->roles()->attach($role->id);

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
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
        $this->authorize("deleteRole", $user);

        if ($user->roles()->where('id', $role->id)->count())
            $user->roles()->detach($role->id);

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
        ]);
    }

    /**
     * Update user level
     *
     * @param UserLevelRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLevel(UserLevelRequest $request, User $user)
    {
        $this->authorize("updateLevel", $user);

        $user->level = $request->validated('level');
        $user->save();

        if (!in_array($user->level, [User::LEVEL_ADMIN])) {
            $user->roles()->detach();
        }

        return response()->json([
            "success" => true,
            "user" => new UserResource($user)
        ]);
    }
}