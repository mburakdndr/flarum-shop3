<?php

namespace Flarum\Shop\Payment;

use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripePaymentMethod implements PaymentMethodInterface
{
    public function process($amount, $data)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method' => $data['payment_method_id'],
                'confirm' => true,
                'return_url' => $data['return_url']
            ]);

            return new PaymentResult(
                $paymentIntent->status === 'succeeded',
                $paymentIntent->status,
                $paymentIntent->id
            );
        } catch (ApiErrorException $e) {
            return new PaymentResult(false, 'failed', null, $e->getMessage());
        }
    }
} 