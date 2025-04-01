<?php

namespace Flarum\Shop\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Flarum\Http\RequestUtil;
use Flarum\Shop\Api\Serializer\ProductSerializer;
use Flarum\Shop\Product\ProductRepository;
use Psr\Http\Message\ServerRequestInterface;

class ListProductsController extends AbstractListController
{
    protected $serializer = ProductSerializer::class;

    protected function data(ServerRequestInterface $request, array $data)
    {
        $actor = RequestUtil::getActor($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        return app(ProductRepository::class)
            ->query()
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
} 