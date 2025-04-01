import app from 'flarum/forum/app';
import Page from 'flarum/common/components/Page';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import Button from 'flarum/common/components/Button';
import CartItemList from './CartItemList';

export default class CartPage extends Page {
  oninit(vnode) {
    super.oninit(vnode);

    this.loading = true;
    this.cartItems = [];
    this.total = 0;

    this.loadCart();
  }

  view() {
    if (!app.session.user) {
      return (
        <div className="CartPage">
          <div className="container">
            <p>{app.translator.trans('flarum-shop.forum.cart.must_be_logged_in')}</p>
          </div>
        </div>
      );
    }

    return (
      <div className="CartPage">
        <div className="container">
          <h2>{app.translator.trans('flarum-shop.forum.cart.title')}</h2>

          {this.loading ? (
            <LoadingIndicator />
          ) : (
            <div>
              <CartItemList items={this.cartItems} />

              <div className="CartPage-total">
                <strong>{app.translator.trans('flarum-shop.forum.cart.total')}:</strong> ${this.total}
              </div>

              {this.cartItems.length > 0 && (
                <Button
                  className="Button Button--primary"
                  onclick={() => this.checkout()}
                  loading={this.checkingOut}
                >
                  {app.translator.trans('flarum-shop.forum.cart.checkout_button')}
                </Button>
              )}
            </div>
          )}
        </div>
      </div>
    );
  }

  loadCart() {
    app.store
      .find('cart-items')
      .then((items) => {
        this.cartItems = items;
        this.total = items.reduce((sum, item) => sum + item.price() * item.quantity(), 0);
        this.loading = false;
        m.redraw();
      })
      .catch((error) => {
        this.loading = false;
        m.redraw();
      });
  }

  checkout() {
    this.checkingOut = true;

    app.store
      .createRecord('orders')
      .save()
      .then((order) => {
        app.modal.show(() => import('./CheckoutModal'), { order });
      })
      .catch(() => {
        this.checkingOut = false;
        m.redraw();
      });
  }
} 