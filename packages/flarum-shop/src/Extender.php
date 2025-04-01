<?php

namespace Flarum\Shop;

use Flarum\Extend;
use Flarum\Shop\Controllers;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less')
        ->route('/shop', 'shop.page', Controllers\ShopPageController::class),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    (new Extend\Locales(__DIR__.'/resources/locale')),

    function () {
        // Event listeners can be added here
    },

    // Migrations
    (new Extend\Migration())
        ->createTable('flarum_shop_products', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->timestamps();
        })
        ->createTable('flarum_shop_orders', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('products');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        })
        ->createTable('flarum_shop_payments', function ($table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('status');
            $table->timestamps();
        }),

    // API Routes for Admin and Forum
    (new Extend\Routes('api'))
        ->post('/shop/products', 'shop.products.create', Controllers\CreateProductController::class)
        ->patch('/shop/products/{id}', 'shop.products.update', Controllers\UpdateProductController::class)
        ->delete('/shop/products/{id}', 'shop.products.delete', Controllers\DeleteProductController::class)
        ->get('/shop/products', 'shop.products.list', Controllers\ListProductsController::class)
        ->post('/shop/cart', 'shop.cart.add', Controllers\CartController::class)
        ->post('/shop/orders', 'shop.orders.create', Controllers\CreateOrderController::class)
        ->get('/shop/orders', 'shop.orders.list', Controllers\ListOrdersController::class)
        ->post('/shop/payment/stripe', 'shop.payment.stripe', Controllers\StripePaymentController::class)
        ->post('/shop/payment/paypal', 'shop.payment.paypal', Controllers\PayPalPaymentController::class)
        ->post('/shop/payment/iyzico', 'shop.payment.iyzico', Controllers\IyzicoPaymentController::class),
];