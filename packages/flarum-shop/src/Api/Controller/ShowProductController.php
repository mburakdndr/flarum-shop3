<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Shop\Model\Product;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ShowProductController extends AbstractShowController
{
    public $serializer = ProductSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        return Product::findOrFail($id);
    }
} 