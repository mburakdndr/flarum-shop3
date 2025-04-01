<?php

namespace Flarum\Shop\Payment;

use Flarum\Shop\Payment\PaymentMethodInterface;

class PaymentManager
{
    protected $container;
    protected $methods = [];

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function registerMethod($name, PaymentMethodInterface $method)
    {
        $this->methods[$name] = $method;
    }

    public function getMethod($name)
    {
        return $this->methods[$name] ?? null;
    }

    public function getMethods()
    {
        return $this->methods;
    }
} 