<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class RoleController extends Controller
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
            "roles" => RoleResource::collection(Role::whereNotNull("id")->paginate(18)),
            'permissibles' => Role::allowedPermissibles(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
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
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
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
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        //
    }
}
