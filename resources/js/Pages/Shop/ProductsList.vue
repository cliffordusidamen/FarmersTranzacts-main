<template>
    <app-layout :show-categories="false">
        <div v-if="!productsAvailable()" class="border p-3 rounded-md lead">
            No product available at the moment!
        </div>

        <div v-else class="page-content">
            <div class="container">
                <div class="row mt-3 justify-content-center">
                    <div class="col-lg-3">
                        <h4>Categories</h4>
                        <div class="list-group list-group-flush">
                            <a v-for="category in $page.props.product_categories" :key="category.id" href="#" class="list-group-item list-group-item-action">
                                {{ category.name }}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <p class="text-muted my-2 lead">
                            {{ getShownPortion() }}
                        </p>

                        <div class="products mb-3">
                            <div class="row justify-content-center">
                                <div v-for="product in $page.props.products.data" :key="product.id" class="col-11 col-sm-6 col-md-4 col-lg-4">
                                    <div class="product product-7 text-center mb-3 shadow">
                                        <figure class="product-media bg-light">
                                            <span v-if="Number(product.in_stock) <= 0" class="product-label label-out">Out of Stock</span>
                                            <a href="#">
                                                <img src="/img/default_product.png" alt="Product image" class="product-image">
                                            </a>

                                            <div class="product-action-vertical">
                                                <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                                                <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                                                <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->
                                        </figure><!-- End .product-media -->

                                        <div class="product-body">
                                            <div class="product-cat">
                                                <a href="#">{{ product.category.name }}</a>
                                            </div><!-- End .product-cat -->
                                            <h3 class="product-title"><a href="#">{{ product.name }}</a></h3><!-- End .product-title -->
                                            <div class="product-price">
                                                &#8358;{{ Number(product.price).toLocaleString() }}
                                            </div><!-- End .product-price -->
                                            <div class="ratings-container">
                                                <div class="ratings">
                                                    <div class="ratings-val" style="width: 20%;"></div><!-- End .ratings-val -->
                                                </div><!-- End .ratings -->
                                                <span class="ratings-text">( 2 Reviews )</span>
                                            </div><!-- End .rating-container -->

                                            <div v-if="product.images > 0" class="product-nav product-nav-thumbs">
                                                <a href="#" class="active">
                                                    <img src="assets/images/products/product-4-thumb.jpg" alt="product desc">
                                                </a>
                                                <a href="#">
                                                    <img src="assets/images/products/product-4-2-thumb.jpg" alt="product desc">
                                                </a>

                                                <a href="#">
                                                    <img src="assets/images/products/product-4-3-thumb.jpg" alt="product desc">
                                                </a>
                                            </div><!-- End .product-nav -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->

                                </div>
                            </div>
                        </div><!-- End .products(all) -->

                        <nav v-if="$page.props.products.links.length > 0" aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li v-for="link in $page.props.products.links" :key="link.label" class="page-item" :class="{ 'active': Number(link.label) == $page.props.products.current_page }">
                                    <inertia-link class="page-link" :href="link.url" v-html="link.label"></inertia-link>
                                </li>
                            </ul>
                        </nav><!-- End .pagination -->

                    </div><!-- End .col-lg-9 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
    components: {
        AppLayout,
    },

    methods: {
        productsAvailable() {
            return this.$page.props.products.data.length > 0;
        },

        getShownPortion() {
            const prevPage = this.$page.props.products.current_page - 1;
            const prevPageLastSerialNumber = (prevPage *  this.$page.props.products.per_page)
            const firstItemSerialNumber = prevPageLastSerialNumber + 1;
            const lastItemSerialNumber = prevPageLastSerialNumber + this.$page.props.products.data.length;

            return `Showing ${firstItemSerialNumber} - ${lastItemSerialNumber} of ${this.$page.props.products.total} `;
        },
    }
}
</script>
