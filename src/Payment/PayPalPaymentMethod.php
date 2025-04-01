<?php

namespace Flarum\Shop\Payment;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class PayPalPaymentMethod implements PaymentMethodInterface
{
    public function getName()
    {
        return 'paypal';
    }

    public function getDescription()
    {
        return 'Pay with PayPal';
    }

    public function processPayment($amount, $currency)
    {
        $clientId = config('flarum-shop.paypal.client_id');
        $clientSecret = config('flarum-shop.paypal.client_secret');
        $isSandbox = config('flarum-shop.paypal.sandbox', true);

        $environment = $isSandbox
            ? new SandboxEnvironment($clientId, $clientSecret)
            : new ProductionEnvironment($clientId, $clientSecret);

        $client = new PayPalHttpClient($environment);

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $currency,
                    'value' => $amount
                ]
            ]]
        ];

        $order = $client->execute($request);

        return [
            'orderId' => $order->result->id,
        ];
    }
} 