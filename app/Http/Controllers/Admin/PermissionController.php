<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'rulables_types' => array_keys(Permission::RULABLES),
            'rulables_actions' => Permission::RULABLES_ACTIONS,
            'permissions' => Permission::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $validated = $request->validated();

        $permission = Permission::create($validated);

        return response([
            'success' => true,
            'rulables_types' => array_keys(Permission::RULABLES),
            'rulables_actions' => Permission::RULABLES_ACTIONS,
            'permission' => $permission
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return response()->json([
            'success' => true,
            'rulables' => array_keys(Permission::RULABLES),
            'rulables_actions' => Permission::RULABLES_ACTIONS,
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PermissionRequest $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        $validated = $request->validated();

        $permission->update([
            'name' => $validated['name'],
            'list' => $permission->makeList($validated)
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            "success" => true
        ]);
    }
}
