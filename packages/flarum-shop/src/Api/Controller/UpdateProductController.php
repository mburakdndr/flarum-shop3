<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Shop\Model\Product;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateProductController extends AbstractShowController
{
    public $serializer = ProductSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');
        $data = Arr::get($request->getParsedBody(), 'data', []);

        $product = Product::findOrFail($id);

        if ($actor->id !== $product->seller_id && !$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }

        $product->update([
            'name' => Arr::get($data, 'attributes.name', $product->name),
            'description' => Arr::get($data, 'attributes.description', $product->description),
            'price' => Arr::get($data, 'attributes.price', $product->price),
            'stock' => Arr::get($data, 'attributes.stock', $product->stock),
            'image_url' => Arr::get($data, 'attributes.image_url', $product->image_url),
            'status' => Arr::get($data, 'attributes.status', $product->status)
        ]);

        return $product;
    }
} 