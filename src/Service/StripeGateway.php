<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class StripeGateway
{
    private string $secretKey;
    private string $publishableKey;
    private ?string $webhookSecret;

    public function __construct(string $secretKey, string $publishableKey, ?string $webhookSecret = null)
    {
        $this->secretKey = $secretKey;
        $this->publishableKey = $publishableKey;
        $this->webhookSecret = $webhookSecret;

        Stripe::setApiKey($this->secretKey);
    }

    public function createPaymentIntent(int $amount, string $currency = 'eur', array $metadata = []): PaymentIntent
    {
        return PaymentIntent::create([
            'amount' => 1000,// $amount,
            'currency' => $currency,
            'metadata' => $metadata,
        ]);
    }

    public function constructEvent(string $payload, string $sigHeader)
    {
        if (!$this->webhookSecret) {
            throw new \RuntimeException('Webhook secret not configured');
        }

        return Webhook::constructEvent(
            $payload,
            $sigHeader,
            $this->webhookSecret
        );
    }

    public function getPublishableKey(): string
    {
        return $this->publishableKey;
    }
}
