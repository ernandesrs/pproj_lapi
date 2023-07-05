<?php

namespace App\Services\Payments;

use App\Exceptions\Dash\Pagarme\RefundPaymentFailException;
use App\Exceptions\Dash\PaymentFailException;
use App\Exceptions\Dash\Payments\InvalidCardException;
use App\Models\Payment\Card;

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
     * @return Card|null
     */
    public function createCard(array $validated)
    {
        $response = $this->pagarme->cards()->create([
            "card_holder_name" => $validated["holder_name"],
            "card_number" => $validated["number"],
            "card_expiration_date" => $validated["expiration_date"],
            "card_cvv" => $validated["cvv"]
        ]);

        if (!($response->valid ?? null)) {
            throw new InvalidCardException();
        }

        return \Auth::user()->paymentMethods()->firstOrCreate()->cards()->create([
            "name" => $validated['name'] ?? ucfirst($response->brand) . ' Final ' . $response->last_digits,
            "holder_name" => $response->holder_name,
            "expiration_date" => $response->expiration_date,
            "hash" => $response->id,
            "last_digits" => $response->last_digits,
            "brand" => $response->brand
        ]);
    }

    /**
     * Create a transaction
     *
     * @param Card $card
     * @param float $amount
     * @param int $installments
     * @param array $metadata
     * @return array
     */
    public function createTransaction(Card $card, float $amount, int $installments = 1, array $metadata = [])
    {
        $data = [
            "amount" => round($amount * 100, 0),
            "installments" => $installments,
            "card_id" => $card->hash,
            "payment_method" => "credit_card",
            "metadata" => $metadata
        ];

        $response = $this->pagarme->transactions()->create($data);
        if (!$response->id ?? false) {
            throw new PaymentFailException();
        }

        if (!in_array($response->status, ["processing", "authorized", "paid", "waiting_payment"])) {
            throw new("App\\Exceptions\\Dash\\Pagarme\\" . ucfirst($response->status) . "PaymentException")();
        }

        return [
            "success" => true,
            "status" => $response->status,
            "gateway" => "pagarme",
            "transaction_id" => $response->id
        ];
    }

    /**
     * Full refund
     *
     * @param string $transactionId
     * @param array $metadata
     * @return array
     */
    public function fullRefund(string $transactionId, array $metadata = [])
    {
        $data = [
            'id' => $transactionId,
            'metadata' => $metadata
        ];

        $response = $this->pagarme->transactions()->refund($data);
        if (!in_array($response->status, ["refunded", "pending_refund"])) {
            throw new RefundPaymentFailException();
        }

        return [
            "success" => true,
            "status" => $response->status,
            "gateway" => "pagarme",
            "transaction_id" => $response->id
        ];
    }
}