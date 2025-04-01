<?php

namespace Flarum\Shop\Payment;

class PaymentResult
{
    protected $success;
    protected $status;
    protected $transactionId;
    protected $message;
    protected $redirectUrl;

    public function __construct($success, $status, $transactionId, $message = null, $redirectUrl = null)
    {
        $this->success = $success;
        $this->status = $status;
        $this->transactionId = $transactionId;
        $this->message = $message;
        $this->redirectUrl = $redirectUrl;
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }
} 