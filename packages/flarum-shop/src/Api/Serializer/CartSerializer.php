<?php

namespace Flarum\Shop\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\UserSerializer;

class CartSerializer extends AbstractSerializer
{
    protected $type = 'cart-items';

    protected function getDefaultAttributes($cartItem)
    {
        return [
            'quantity' => (int) $cartItem->quantity,
            'createdAt' => $this->formatDate($cartItem->created_at),
            'updatedAt' => $this->formatDate($cartItem->updated_at)
        ];
    }

    protected function user($cartItem)
    {
        return $this->hasOne($cartItem, UserSerializer::class);
    }

    protected function product($cartItem)
    {
        return $this->hasOne($cartItem, ProductSerializer::class);
    }
} 