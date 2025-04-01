<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Shop\Model\Product;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateProductController extends AbstractCreateController
{
    public $serializer = ProductSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = Arr::get($request->getParsedBody(), 'data', []);

        return Product::create([
            'seller_id' => $actor->id,
            'name' => Arr::get($data, 'attributes.name'),
            'description' => Arr::get($data, 'attributes.description'),
            'price' => Arr::get($data, 'attributes.price'),
            'stock' => Arr::get($data, 'attributes.stock'),
            'image_url' => Arr::get($data, 'attributes.image_url'),
            'status' => 'active'
        ]);
    }
} 