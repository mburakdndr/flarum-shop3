<?php

namespace Flarum\Shop\Content;

use Flarum\Frontend\Document;
use Flarum\Frontend\Driver\TitleDriverInterface;
use Flarum\User\Access\Gate;
use Flarum\User\User;
use Psr\Http\Message\ServerRequestInterface as Request;

class ShopPage
{
    protected $title;

    public function __construct(TitleDriverInterface $title)
    {
        $this->title = $title;
    }

    public function __invoke(Document $document, Request $request)
    {
        $document->title = $this->title->toTitle('Shop');
        $document->content = view('flarum-shop::shop');
    }
} 