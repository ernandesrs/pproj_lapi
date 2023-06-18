<?php

namespace App\Services\Payments;

use App\Exceptions\Dash\Payments\InvalidCreditCardException;
use App\Models\CreditCard;

class Pagarme
{
    /**
     * Pagarme
     *
     * @var \PagarMe\PagarMe
     */
    private $pagarme;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagarme = new \PagarMe\Client(env("GATEWAY_PAGARME_API_TEST"));
    }

    /**
     * Create a credit: validate card and save on data base
     *
     * @param array $validated
     * @return CreditCard|null
     */
    public function createCreditCard(array $validated)
    {
        $data = [
            "card_holder_name" => $validated["card_holder_name"],
            "card_number" => $validated["card_number"],
            "card_expiration_date" => $validated["card_expiration_date"],
            "card_cvv" => $validated["card_cvv"]
        ];

        $response = $this->pagarme->cards()->create($data);
        if (!($response->valid ?? null)) {
            throw new InvalidCreditCardException();
        }

        return \Auth::user()->creditCards()->create([
            "holder_name" => $response->holder_name,
            "expiration_date" => $response->expiration_date,
            "hash" => $response->id,
            "last_digits" => $response->last_digits,
            "brand" => $response->brand,
        ]);
    }
}