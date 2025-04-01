<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Flarum\Shop\Model\Product;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListProductsController extends AbstractListController
{
    public $serializer = ProductSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $query = Product::query();

        if ($sellerId = $request->getQueryParams()['seller_id'] ?? null) {
            $query->where('seller_id', $sellerId);
        }

        if ($status = $request->getQueryParams()['status'] ?? null) {
            $query->where('status', $status);
        }

        return $query->paginate($request->getQueryParams()['page'] ?? 15);
    }
} 