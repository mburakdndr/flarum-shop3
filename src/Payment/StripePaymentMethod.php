<?php

namespace Flarum\Shop\Payment;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentMethod implements PaymentMethodInterface
{
    public function getName()
    {
        return 'stripe';
    }

    public function getDescription()
    {
        return 'Pay with Stripe';
    }

    public function processPayment($amount, $currency)
    {
        Stripe::setApiKey(config('flarum-shop.stripe.secret_key'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount * 100, // Convert to cents
            'currency' => $currency,
        ]);

        return [
            'clientSecret' => $paymentIntent->client_secret,
        ];
    }
} 