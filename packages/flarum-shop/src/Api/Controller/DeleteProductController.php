<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\Shop\Model\Product;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteProductController extends AbstractDeleteController
{
    protected function delete(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');

        $product = Product::findOrFail($id);

        if ($actor->id !== $product->seller_id && !$actor->isAdmin()) {
            throw new PermissionDeniedException();
        }

        $product->delete();
    }
} 