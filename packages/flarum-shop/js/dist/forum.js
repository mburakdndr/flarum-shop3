(function() {
    app.initializers.add('flarum/shop', function() {
        app.routes.shop = { path: '/shop', component: ShopPage };

        class ShopPage extends Component {
            oninit(vnode) {
                super.oninit(vnode);
                this.products = [];
                this.loadProducts();
            }

            async loadProducts() {
                const res = await app.store.find('products');
                this.products = res;
            }

            view() {
                return m('div', [
                    m('h2', 'Mağaza'),
                    m('div.product-list', this.products.map(product => {
                        return m('div.product-item', [
                            m('h3', product.name),
                            m('p', product.description),
                            m('p', `Fiyat: ${product.price} ₺`),
                            m('button', { onclick: () => this.addToCart(product) }, 'Sepete Ekle')
                        ]);
                    }))
                ]);
            }

            addToCart(product) {
                app.store.createRecord('cart', { product_id: product.id });
            }
        }
    });
})();