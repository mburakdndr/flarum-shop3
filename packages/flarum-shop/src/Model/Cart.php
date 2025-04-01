<?php

namespace Flarum\Shop\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class Cart extends AbstractModel
{
    protected $table = 'shop_carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 