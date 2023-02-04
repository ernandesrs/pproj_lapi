<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\Admin\HasDependentsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\FilterService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Filter Service
     * @var FilterService
     */
    private $filterService;

    public function __construct()
    {
        $this->filterService = new FilterService(new Role());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $this->authorize("viewAny", Role::class);

        $roles = $this->filterService->filter($request);

        return response()->json([
            "success" => true,
            "roles" => RoleResource::collection($roles->withQueryString())->response()->getData(),
            'permissibles' => Role::allowedPermissibles(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleRequest $request)
    {
        $this->authorize("create", Role::class);

        $role = Role::create($request->validated());
        $role->permissibles = json_decode($role->permissibles);

        return response()->json([
            'success' => true,
            'role' => new RoleResource($role)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        $this->authorize("view", $role);

        return response()->json([
            'success' => true,
            'role' => new RoleResource($role),
            'permissibles' => Role::allowedPermissibles(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleRequest  $request
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize("update", $role);

        $role->update($request->validated());
        $role->permissibles = json_decode($role->permissibles);

        return response()->json([
            'success' => true,
            'role' => new RoleResource($role),
            'permissibles' => Role::allowedPermissibles(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $this->authorize("delete", $role);

        if ($role->users()->count()) {
            throw new HasDependentsException();
        }

        $role->delete();

        return response()->json([
            'success' => true
        ]);
    }
}