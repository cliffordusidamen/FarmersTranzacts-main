<template>
    <div class="product product-7 text-center mb-3 shadow">
        <figure class="product-media bg-light">
            <span
                v-if="!Number(product.in_stock)"
                class="product-label label-out"
                >Out of Stock</span
            >
            <inertia-link
                :href="route('shop.show_product', { slug: product.slug })"
                class="image-link"
            >
                <img
                    :src="product.feature_image"
                    alt="Product image"
                    class="product-image"
                />
            </inertia-link>

            <div class="product-action-vertical d-none">
                <a href="#" class="btn-product-icon btn-wishlist btn-expandable"
                    ><span>add to wishlist</span></a
                >
                <a
                    href="popup/quickView.html"
                    class="btn-product-icon btn-quickview"
                    title="Quick view"
                    ><span>Quick view</span></a
                >
                <a href="#" class="btn-product-icon btn-compare" title="Compare"
                    ><span>Compare</span></a
                >
            </div>
            <!-- End .product-action-vertical -->

            <div v-if="Number(product.in_stock)" class="product-action">
                <cart-update-button :product="product" />
            </div>
            <!-- End .product-action -->
        </figure>
        <!-- End .product-media -->

        <div class="product-body">
            <div class="product-cat">
                <inertia-link
                    :href="
                        route('shop.categorized', {
                            slug: product.category.slug,
                        })
                    "
                >
                    {{ product.category.name }}
                </inertia-link>
            </div>
            <!-- End .product-cat -->
            <h3 class="product-title">
                <inertia-link
                    :href="route('shop.show_product', { slug: product.slug })"
                >
                    {{ product.name }}
                </inertia-link>
            </h3>
            <!-- End .product-title -->
            <div class="product-price">
                &#8358;{{ Number(product.price).toLocaleString() }}
            </div>
            <!-- End .product-price -->

            <div class="mt-n4 product-packaging">
                per {{ product.packaging.name }}
            </div>

            <div
                v-if="product.images > 0"
                class="product-nav product-nav-thumbs"
            >
                <a href="#" class="active">
                    <img
                        src="assets/images/products/product-4-thumb.jpg"
                        alt="product desc"
                    />
                </a>
                <a href="#">
                    <img
                        src="assets/images/products/product-4-2-thumb.jpg"
                        alt="product desc"
                    />
                </a>

                <a href="#">
                    <img
                        src="assets/images/products/product-4-3-thumb.jpg"
                        alt="product desc"
                    />
                </a>
            </div>
            <!-- End .product-nav -->
        </div>
        <!-- End .product-body -->
    </div>
    <!-- End .product -->
</template>

<script>
import CartUpdateButton from '@/Components/Controls/CartUpdateButton.vue'

export default {
    components: {
        CartUpdateButton,
    },

    props: {
        product: {
            type: Object,
            required: true,
        },
    },
};
</script>
