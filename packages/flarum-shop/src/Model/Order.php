<?php

namespace Flarum\Shop\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class Order extends AbstractModel
{
    protected $table = 'shop_orders';

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'payment_status'
    ];

    protected $casts = [
        'total_amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
} 