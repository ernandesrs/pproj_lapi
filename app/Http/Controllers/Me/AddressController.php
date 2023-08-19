<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Http\Requests\Me\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
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
            "addresses" => AddressResource::collection(\Auth::user()->addresses()->get())
                ->response()->getData()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddressRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddressRequest $request)
    {
        $address = $request->user()->addresses()->create($request->validated());
        return response()->json([
            "success" => true,
            "address" => new AddressResource($address)
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
        $address = \Auth::user()->addresses()->where("id", $id)->firstOrFail();
        return response()->json([
            "success" => true,
            "address" => new AddressResource($address)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AddressRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AddressRequest $request, int $id)
    {
        $address = \Auth::user()->addresses()->where("id", $id)->firstOrFail();

        $address->update($request->validated());

        return response()->json([
            "success" => true,
            "address" => new AddressResource($address)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $address = \Auth::user()->addresses()->where("id", $id)->firstOrFail();
        $address->delete();
        return response()->json([
            "success" => true
        ]);
    }
}