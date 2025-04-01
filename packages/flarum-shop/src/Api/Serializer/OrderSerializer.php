<?php

namespace Flarum\Shop\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\UserSerializer;

class OrderSerializer extends AbstractSerializer
{
    protected $type = 'orders';

    protected function getDefaultAttributes($order)
    {
        return [
            'total_amount' => (float) $order->total_amount,
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'createdAt' => $this->formatDate($order->created_at),
            'updatedAt' => $this->formatDate($order->updated_at)
        ];
    }

    protected function user($order)
    {
        return $this->hasOne($order, UserSerializer::class);
    }

    protected function items($order)
    {
        return $this->hasMany($order, OrderItemSerializer::class);
    }
} 