<?php

namespace Flarum\Shop\Product;

use Flarum\User\Access\Gate;

class ProductRepository
{
    protected $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function query()
    {
        return Product::query();
    }
} 