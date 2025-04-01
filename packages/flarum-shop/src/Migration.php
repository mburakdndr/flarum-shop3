<?php

namespace Flarum\Shop;

use Flarum\Database\AbstractMigration;
use Illuminate\Database\Schema\Blueprint;

class Migration extends AbstractMigration
{
    public function up()
    {
        $this->schema->create('flarum_shop_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->timestamps();
        });

        $this->schema->create('flarum_shop_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('products');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        $this->schema->create('flarum_shop_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('flarum_shop_products');
        $this->schema->dropIfExists('flarum_shop_orders');
        $this->schema->dropIfExists('flarum_shop_payments');
    }
}