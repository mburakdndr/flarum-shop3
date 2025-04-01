import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import IndexPage from 'flarum/forum/components/IndexPage';
import HeaderPrimary from 'flarum/forum/components/HeaderPrimary';
import LinkButton from 'flarum/common/components/LinkButton';
import ShopPage from './components/ShopPage';
import CartPage from './components/CartPage';
import OrdersPage from './components/OrdersPage';

app.initializers.add('flarum-shop', () => {
  app.routes.shop = {
    path: '/shop',
    component: ShopPage,
  };

  app.routes.cart = {
    path: '/shop/cart',
    component: CartPage,
  };

  app.routes.orders = {
    path: '/shop/orders',
    component: OrdersPage,
  };

  extend(HeaderPrimary.prototype, 'items', function(items) {
    items.add(
      'shop',
      <LinkButton icon="fas fa-shopping-cart" href={app.route('shop')}>
        {app.translator.trans('flarum-shop.forum.nav.shop_button')}
      </LinkButton>
    );
  });
}); 