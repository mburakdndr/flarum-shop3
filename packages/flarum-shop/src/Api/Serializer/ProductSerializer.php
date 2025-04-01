<?php

namespace Flarum\Shop\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\UserSerializer;

class ProductSerializer extends AbstractSerializer
{
    protected $type = 'products';

    protected function getDefaultAttributes($product)
    {
        return [
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'stock' => (int) $product->stock,
            'image_url' => $product->image_url,
            'status' => $product->status,
            'createdAt' => $this->formatDate($product->created_at),
            'updatedAt' => $this->formatDate($product->updated_at)
        ];
    }

    protected function seller($product)
    {
        return $this->hasOne($product, UserSerializer::class);
    }
} 