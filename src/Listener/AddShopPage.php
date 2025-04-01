<?php

namespace Flarum\Shop\Listener;

use Flarum\Event\ConfigureForumRoutes;
use Illuminate\Contracts\Events\Dispatcher;

class AddShopPage
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureForumRoutes::class, [$this, 'configureForumRoutes']);
    }

    public function configureForumRoutes(ConfigureForumRoutes $event)
    {
        $event->get('/shop', 'shop.index', 'Flarum\Shop\Content\ShopPage');
    }
} 