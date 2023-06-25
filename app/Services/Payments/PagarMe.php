<?php

namespace App\Services\Payments;

use App\Exceptions\Dash\Pagarme\RefundPaymentFailException;
use App\Exceptions\Dash\PaymentFailException;
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

        $newCreditCard = \Auth::user()->creditCards()->create([
            "holder_name" => $response->holder_name,
            "expiration_date" => $response->expiration_date,
            "hash" => $response->id,
            "last_digits" => $response->last_digits,
            "brand" => $response->brand
        ]);

        $newCreditCard->number = "**** **** **** " . $response->last_digits;

        return $newCreditCard;
    }

    /**
     * Create a transaction
     *
     * @param CreditCard $card
     * @param float $amount
     * @param int $installments
     * @param array $metadata
     * @return array
     */
    public function createTransaction(CreditCard $card, float $amount, int $installments = 1, array $metadata = [])
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