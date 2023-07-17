<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Http\Resources\Admin\SettingResource;
use App\Models\Admin\Setting;

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
            "settings" => SettingResource::collection(Setting::all())
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
            "setting" => new SettingResource($setting)
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
        try {
            $setting = (new Setting)->where("id", $id)->firstOrFail();

            $model = (new("\\App\\Models\\Admin\\" . $setting->name))->where("id", $setting->id)->firstOrFail();

            $this->authorize('update', $model);

            $model->update($request->validated());
        } catch (\Exception $e) {
            throw new NotFoundException;
        }

        return response()->json([
            "success" => true,
            "setting" => new SettingResource($model)
        ]);
    }
}