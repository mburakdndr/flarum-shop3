<?php

namespace Flarum\Shop\Payment;

use Flarum\Settings\SettingsRepositoryInterface;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentManager
{
    protected $settings;
    protected $methods = [];

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        
        // Initialize Stripe
        Stripe::setApiKey($this->settings->get('shop.stripe_secret_key'));
        
        // Register payment methods
        $this->registerMethod('stripe', new StripePaymentMethod());
        $this->registerMethod('paypal', new PayPalPaymentMethod());
    }

    public function registerMethod($name, PaymentMethodInterface $method)
    {
        $this->methods[$name] = $method;
    }

    public function process($method, $amount, $data)
    {
        if (!isset($this->methods[$method])) {
            throw new \InvalidArgumentException("Payment method '$method' not found");
        }

        return $this->methods[$method]->process($amount, $data);
    }
} 