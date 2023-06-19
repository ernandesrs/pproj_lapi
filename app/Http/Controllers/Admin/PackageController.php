<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Models\Package;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize("viewAny", Package::class);

        return response()->json([
            "success" => true,
            "packages" => Package::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PackageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PackageRequest $request)
    {
        $this->authorize("create", Package::class);

        $package = Package::create($request->validated());

        return response()->json([
            "success" => true,
            "package" => $package
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Package  $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Package $package)
    {
        $this->authorize("view", $package);

        return response()->json([
            "success" => true,
            "package" => $package
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PackageRequest  $request
     * @param  Package  $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PackageRequest $request, Package $package)
    {
        $this->authorize("update", $package);

        $package->update($request->validated());

        return response()->json([
            "success" => true,
            "package" => $package
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Package  $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Package $package)
    {
        $this->authorize("delete", $package);

        $package->delete();

        return response()->json([
            "success" => true
        ]);
    }
}