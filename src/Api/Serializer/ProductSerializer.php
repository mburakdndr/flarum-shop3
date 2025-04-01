<?php

namespace Flarum\Shop\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Shop\Product\Product;

class ProductSerializer extends AbstractSerializer
{
    protected $type = 'products';

    protected function getDefaultAttributes($product)
    {
        if (!($product instanceof Product)) {
            throw new \InvalidArgumentException(
                get_class($this).' can only serialize instances of '.Product::class
            );
        }

        return [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'image' => $product->image,
            'createdAt' => $this->formatDate($product->created_at),
            'updatedAt' => $this->formatDate($product->updated_at),
        ];
    }
} 