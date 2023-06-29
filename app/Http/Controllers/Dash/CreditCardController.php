<?php

namespace App\Http\Controllers\Dash;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditCardRequest;
use App\Models\CreditCard;
use App\Services\FilterService;
use App\Services\Payments\Pagarme;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $cards = \Auth::user()->creditCards();

        return response()->json([
            "success" => true,
            "data" => (new FilterService($cards, true))->filter($request)->withQueryString()
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
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        /**
         * @var CreditCard $card
         */
        $card = \Auth::user()->creditCards()->where("id", "=", $id)->first();
        if (!$card) {
            throw new NotFoundException("Credit card not found.");
        }

        $card->update(['name' => $request->validate(["name" => ["required", "string", "max:50"]])['name']]);

        return response()->json([
            "success" => true,
            "card" => $card
        ]);
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