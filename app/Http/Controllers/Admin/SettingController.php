<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Http\Resources\Admin\SettingResource;
use App\Models\Admin\Setting;
use App\Models\Admin\SettingAll;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', Setting::class);

        return response()->json([
            "success" => true,
            "settings" => [
                "all" => new SettingResource(SettingAll::first())
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $this->authorize('create', Setting::class);

        $settingAll = SettingAll::create([
            'app_name' => 'LAPI'
        ]);
        return response()->json([
            "success" => "true",
            "SettingAll" => $settingAll
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $setting = Setting::where("id", $id)->firstOrFail();

        $this->authorize('view', $setting);

        return response()->json([
            "success" => true,
            "setting" => $setting
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\SettingRequest  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SettingRequest $request, int $id)
    {
        $model = "\\App\\Models\\Admin\\" . $request->validated('name');

        try {
            $model = (new $model)->where("id", $id)->firstOrFail();

            $this->authorize('update', $model);

            $model->update($request->all());
        } catch (\Exception $e) {
            throw new NotFoundException;
        }

        return response()->json([
            "success" => true,
            "setting" => $model
        ]);
    }
}