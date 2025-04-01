<?php

namespace Flarum\Shop\Payment;

interface PaymentMethodInterface
{
    public function process($amount, $data);
} 