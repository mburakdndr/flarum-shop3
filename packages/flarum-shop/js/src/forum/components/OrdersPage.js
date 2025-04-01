import app from 'flarum/forum/app';
import Page from 'flarum/common/components/Page';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import OrderList from './OrderList';

export default class OrdersPage extends Page {
  oninit(vnode) {
    super.oninit(vnode);

    this.loading = true;
    this.orders = [];

    this.loadOrders();
  }

  view() {
    if (!app.session.user) {
      return (
        <div className="OrdersPage">
          <div className="container">
            <p>{app.translator.trans('flarum-shop.forum.orders.must_be_logged_in')}</p>
          </div>
        </div>
      );
    }

    return (
      <div className="OrdersPage">
        <div className="container">
          <h2>{app.translator.trans('flarum-shop.forum.orders.title')}</h2>

          {this.loading ? (
            <LoadingIndicator />
          ) : (
            <OrderList orders={this.orders} />
          )}
        </div>
      </div>
    );
  }

  loadOrders() {
    app.store
      .find('orders')
      .then((orders) => {
        this.orders = orders;
        this.loading = false;
        m.redraw();
      })
      .catch((error) => {
        this.loading = false;
        m.redraw();
      });
  }
} 