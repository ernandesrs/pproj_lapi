<?php

namespace App\Http\Controllers\Dash\Payment;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResource;
use App\Models\Payment\Card;
use App\Services\Payments\Pagarme;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CardRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CardRequest $request)
    {
        if ($request->user()->paymentMethods()->firstOrFail()->cards()->count() >= 2) {
            return response()->json([
                "success" => false
            ]);
        }

        /**
         * Validate credit card with Paga.me and save credit card hash
         * @var Card
         **/
        $card = (new Pagarme())->createCard($request->validated());

        return response()->json([
            "success" => true,
            "card" => new CardResource($card)
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
         * @var Card $card
         */
        $card = \Auth::user()
            ->paymentMethods()->firstOrFail()
            ->cards()->where("id", "=", $id)->firstOrFail();

        $card->update(['name' => $request->validate(["name" => ["required", "string", "max:50"]])['name']]);

        return response()->json([
            "success" => true,
            "card" => new CardResource($card)
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
        $card = \Auth::user()
            ->paymentMethods()->firstOrFail()
            ->cards()->where("id", $id)->firstOrFail();

        $card->delete();

        return response()->json([
            "success" => true,
        ]);
    }
}