<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Shop\Model\Cart;
use Flarum\Shop\Model\Product;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class AddToCartController extends AbstractCreateController
{
    public $serializer = CartSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data', []);

        $productId = Arr::get($data, 'relationships.product.data.id');
        $quantity = Arr::get($data, 'attributes.quantity', 1);

        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            throw new ValidationException(['quantity' => 'Not enough stock available']);
        }

        $existingCartItem = Cart::where('user_id', $actor->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $quantity
            ]);
            return $existingCartItem;
        }

        return Cart::create([
            'user_id' => $actor->id,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }
} 