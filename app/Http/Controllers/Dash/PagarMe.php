<?php

namespace App\Http\Controllers\Dash;

use App\Models\CreditCard;

class PagarMe
{
    /**
     * Api base
     * @var string
     */
    protected $apiBase;

    /**
     * Api key
     * @var string
     */
    protected $apiKey;

    /**
     * Data
     * @var mixed
     */
    protected $data;

    public function __construct()
    {
        $this->apiBase = "https://api.pagar.me/1";
        $this->apiKey = env("GATEWAY_PAGARME_API_TEST");
    }

    /**
     * Create credit card
     * @param array $validated
     * @return CreditCard|null
     */
    public function createCreditCard(array $validated)
    {
        $this->data = [
            "card_holder_name" => $validated["card_holder_name"],
            "card_number" => $validated["card_number"],
            "card_expiration_date" => $validated["card_expiration_date"],
            "card_cvv" => $validated["card_cvv"]
        ];

        $response = $this->call("/cards", "post");
        if (!($response->id ?? null)) {
            return null;
        }

        return \Auth::user()->creditCards()->create([
            "holder_name" => $response->holder_name,
            "expiration_date" => $response->expiration_date,
            "hash" => $response->id,
            "last_digits" => $response->last_digits,
            "brand" => $response->brand,
        ]);
    }

    /**
     * Request
     * @param string $endpoint
     * @param string|null $method
     * @return \stdClass|null
     */
    private function call(string $endpoint, ?string $method = null)
    {
        $endpoint = $this->apiBase . $endpoint . "?api_key=" . $this->apiKey;
        $method = strtolower($method ?? "get");
        $response = null;

        switch ($method) {
            case "get":
                $response = (\Http::get($endpoint))->json();
                break;
            case "post":
                $response = (\Http::post($endpoint, $this->data))->json();
                break;
        }

        return (object) $response;
    }
}