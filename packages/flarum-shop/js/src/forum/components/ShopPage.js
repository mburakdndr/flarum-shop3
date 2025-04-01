import app from 'flarum/forum/app';
import Page from 'flarum/common/components/Page';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import Button from 'flarum/common/components/Button';
import ProductList from './ProductList';

export default class ShopPage extends Page {
  oninit(vnode) {
    super.oninit(vnode);

    this.loading = true;
    this.products = [];

    this.loadProducts();
  }

  view() {
    return (
      <div className="ShopPage">
        <div className="container">
          <div className="ShopPage-header">
            <h2>{app.translator.trans('flarum-shop.forum.shop.title')}</h2>
            {app.session.user && app.forum.attribute('canSellProducts') && (
              <Button
                className="Button Button--primary"
                icon="fas fa-plus"
                onclick={() => this.addProduct()}
              >
                {app.translator.trans('flarum-shop.forum.shop.add_product_button')}
              </Button>
            )}
          </div>

          {this.loading ? (
            <LoadingIndicator />
          ) : (
            <ProductList products={this.products} />
          )}
        </div>
      </div>
    );
  }

  loadProducts() {
    app.store
      .find('products')
      .then((products) => {
        this.products = products;
        this.loading = false;
        m.redraw();
      })
      .catch((error) => {
        this.loading = false;
        m.redraw();
      });
  }

  addProduct() {
    app.modal.show(() => import('./AddProductModal'));
  }
} 