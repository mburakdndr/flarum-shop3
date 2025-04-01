<?php

namespace Flarum\Shop;

use Flarum\Extend;
use Illuminate\Contracts\Events\Dispatcher;
use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Forum\Content\View;
use Psr\Http\Message\ServerRequestInterface;
use Flarum\Database\Migration;
use Flarum\Admin\Extend\Controller;
use Flarum\Shop\Api\Controller;
use Flarum\Shop\Product;
use Flarum\Shop\Order;
use Flarum\Shop\Cart;

return [
    // Register API routes
    (new Extend\Routes('api'))
        ->post('/shop/products', 'shop.products.create', Controller\CreateProductController::class)
        ->patch('/shop/products/{id}', 'shop.products.update', Controller\UpdateProductController::class)
        ->delete('/shop/products/{id}', 'shop.products.delete', Controller\DeleteProductController::class)
        ->get('/shop/products', 'shop.products.index', Controller\ListProductsController::class)
        ->get('/shop/products/{id}', 'shop.products.show', Controller\ShowProductController::class)
        ->post('/shop/cart/add', 'shop.cart.add', Controller\AddToCartController::class)
        ->post('/shop/orders', 'shop.orders.create', Controller\CreateOrderController::class),

    // Register frontend routes
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/resources/less/forum.less')
        ->route('/shop', 'shop.index')
        ->route('/shop/product/{id}', 'shop.product.show')
        ->route('/shop/cart', 'shop.cart')
        ->route('/shop/orders', 'shop.orders'),

    // Register admin routes and assets
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js')
        ->css(__DIR__ . '/resources/less/admin.less'),

    // Register models
    new Extend\Model(Product::class),
    new Extend\Model(Order::class),
    new Extend\Model(Cart::class),

    // Register permissions
    (new Extend\Policy())
        ->modelPolicy(Product::class, Access\ProductPolicy::class),

    // Register settings
    (new Extend\Settings())
        ->serializeToForum('shop.currency', 'shop.currency')
        ->serializeToForum('shop.stripe_public_key', 'shop.stripe_public_key'),

    // Register locales
    new Extend\Locales(__DIR__ . '/resources/locale'),
];

namespace Flarum\Shop\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\Capsule\Manager as DB;
use Laminas\Diactoros\Response\JsonResponse;
use Flarum\Forum\Content\View;

class CartController {
    public function handle(ServerRequestInterface $request) {
        $data = $request->getParsedBody();
        return new JsonResponse(['message' => 'Ürün sepete eklendi.', 'product' => $data], 201);
    }
}

class CreateOrderController {
    public function handle(ServerRequestInterface $request) {
        $data = $request->getParsedBody();
        
        $order = DB::table('flarum_shop_orders')->insert([
            'user_id' => $data['user_id'],
            'products' => json_encode($data['products']),
            'total_price' => $data['total_price'],
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return new JsonResponse(['message' => 'Sipariş başarıyla oluşturuldu.', 'order' => $order], 201);
    }
}

class ListOrdersController {
    public function handle(ServerRequestInterface $request) {
        $orders = DB::table('flarum_shop_orders')->get();
        return new JsonResponse(['orders' => $orders]);
    }
}
