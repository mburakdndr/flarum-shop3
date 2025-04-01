<?php

namespace Flarum\Shop\Model;

use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;
use Flarum\User\User;

class Product extends AbstractModel
{
    use ScopeVisibilityTrait;

    protected $table = 'shop_products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image_url',
        'status'
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
} 