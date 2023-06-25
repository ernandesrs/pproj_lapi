<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
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
        return response()->json([
            "success" => true,
            "packages" => Package::where("show", "=", true)->get()
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
        return response()->json([
            "success" => true,
            "package" => $package
        ]);
    }
}