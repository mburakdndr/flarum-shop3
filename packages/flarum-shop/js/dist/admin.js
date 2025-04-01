(function() {
    app.initializers.add('flarum/shop-admin', function() {
        app.routes.adminShop = { path: '/admin/shop', component: AdminShopPage };

        class AdminShopPage extends Component {
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
                    m('h2', 'Admin Mağaza Yönetimi'),
                    m('button', { onclick: () => this.createProduct() }, 'Yeni Ürün Ekle'),
                    m('div.product-list', this.products.map(product => {
                        return m('div.product-item', [
                            m('h3', product.name),
                            m('p', product.description),
                            m('p', `Fiyat: ${product.price} ₺`),
                            m('button', { onclick: () => this.editProduct(product) }, 'Düzenle'),
                            m('button', { onclick: () => this.deleteProduct(product) }, 'Sil')
                        ]);
                    }))
                ]);
            }

            createProduct() {
                app.modal.show(CreateProductModal);
            }

            editProduct(product) {
                app.modal.show(EditProductModal, { product });
            }

            deleteProduct(product) {
                if (confirm('Bu ürünü silmek istediğinizden emin misiniz?')) {
                    product.delete();
                    this.loadProducts();
                }
            }
        }

        class CreateProductModal extends Modal {
            oninit(vnode) {
                super.oninit(vnode);
                this.product = { name: '', description: '', price: '', stock: '' };
            }

            view() {
                return m('div', [
                    m('h2', 'Yeni Ürün Ekle'),
                    m('input', {
                        placeholder: 'Ürün Adı',
                        value: this.product.name,
                        oninput: (e) => { this.product.name = e.target.value; }
                    }),
                    m('textarea', {
                        placeholder: 'Ürün Açıklaması',
                        value: this.product.description,
                        oninput: (e) => { this.product.description = e.target.value; }
                    }),
                    m('input', {
                        type: 'number',
                        placeholder: 'Fiyat',
                        value: this.product.price,
                        oninput: (e) => { this.product.price = e.target.value; }
                    }),
                    m('input', {
                        type: 'number',
                        placeholder: 'Stok',
                        value: this.product.stock,
                        oninput: (e) => { this.product.stock = e.target.value; }
                    }),
                    m('button', { onclick: () => this.submit() }, 'Kaydet')
                ]);
            }

            submit() {
                const newProduct = app.store.createRecord('products', this.product);
                newProduct.save().then(() => {
                    app.modal.close();
                    app.router.navigate('/admin/shop');
                });
            }
        }
    });
})();