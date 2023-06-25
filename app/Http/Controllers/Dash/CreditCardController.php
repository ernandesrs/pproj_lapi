<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreditCardRequest;
use App\Models\CreditCard;
use App\Services\Payments\Pagarme;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cards = \Auth::user()->creditCards()->orderBy("created_at", "desc")->get();

        return response()->json([
            "success" => true,
            "cards" => $cards
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreditCardRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreditCardRequest $request)
    {
        /**
         * Validate credit card with Paga.me and save credit card hash
         * @var CreditCard
         **/
        $creditCard = (new Pagarme())->createCreditCard($request->validated());

        return response()->json([
            "success" => true,
            "card" => $creditCard
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
        $card = \Auth::user()->creditCards()->where("id", $id)->first();

        return response()->json([
            "success" => true,
            "card" => $card
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $card = \Auth::user()->creditCards()->where("id", $id)->first();

        if ($card)
            $card->delete();

        return response()->json([
            "success" => true,
        ]);
    }
}