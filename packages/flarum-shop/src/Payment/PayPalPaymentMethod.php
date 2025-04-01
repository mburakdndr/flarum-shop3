<?php

namespace Flarum\Shop\Payment;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;

class PayPalPaymentMethod implements PaymentMethodInterface
{
    public function process($amount, $data)
    {
        try {
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    $data['client_id'],
                    $data['client_secret']
                )
            );

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amountDetails = new Amount();
            $amountDetails->setTotal($amount)
                ->setCurrency('USD');

            $transaction = new Transaction();
            $transaction->setAmount($amountDetails);

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($data['return_url'])
                ->setCancelUrl($data['cancel_url']);

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

            $payment->create($apiContext);

            return new PaymentResult(
                true,
                'pending',
                $payment->getId(),
                null,
                $payment->getApprovalLink()
            );
        } catch (PayPalConnectionException $e) {
            return new PaymentResult(false, 'failed', null, $e->getMessage());
        }
    }
} 