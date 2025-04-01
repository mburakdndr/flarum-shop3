<?php

namespace Flarum\Shop\Payment;

interface PaymentMethodInterface
{
    public function getName();
    public function getDescription();
    public function processPayment($amount, $currency);
} 