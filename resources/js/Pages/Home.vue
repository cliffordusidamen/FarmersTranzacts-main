<template>
    <app-layout>
        <div class="intro-slider-container d-none">
            <div class="container intro-content">
                <div class="row">
                    <div class="col-auto offset-lg-3 intro-col">
                        <h2 class="h4 font-weight-bold">
                            Dashboard
                        </h2>
                    </div>
                </div>
            </div>
        </div><!-- End Intro Container -->

        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div
                            v-if="pageProps.products.length > 0"
                        >
                            <h2 class="title title-border my-4">Featured Items</h2><!-- End .title -->

                            <div ref="productsContainerRef" class="owl-carousel owl-simple owl-nav-top carousel-equal-height carousel-with-shadow mt-4" data-toggle="owl" 
                                data-owl-options='{
                                    "nav": true, 
                                    "dots": false,
                                    "margin": 20,
                                    "loop": false,
                                    "responsive": {
                                        "0": {
                                            "items":2
                                        },
                                        "480": {
                                            "items":2
                                        },
                                        "768": {
                                            "items":3
                                        },
                                        "1200": {
                                            "items":4
                                        }
                                    }
                                }'>
                                <div
                                    class="product mx-4"
                                    v-for="product in pageProps.products"
                                    :key="product.id"
                                >
                                    <figure class="product-media">
                                        <span class="product-label label-top">Top</span>
                                        <inertia-link :href="route('shop.show_product', {slug: product.slug})">
                                            <img src="/img/default_product.png" alt="Product image" class="product-image">
                                        </inertia-link>

                                        <div class="product-action">
                                            <cart-update-button :product="product" />
                                        </div><!-- End .product-action -->
                                    </figure><!-- End .product-media -->

                                    <div class="product-body">
                                        <div class="product-cat">
                                            <inertia-link :href="route('shop.categorized', {slug: product.category.slug})">
                                                {{ product.category.name }}
                                            </inertia-link>
                                        </div><!-- End .product-cat -->
                                        <h3 class="product-title">
                                            <inertia-link :href="route('shop.show_product', {slug: product.slug})">
                                                {{ product.name }}
                                            </inertia-link>
                                        </h3><!-- End .product-title -->
                                        <div class="product-price">
                                            &#8358;{{ Number(product.price).toLocaleString() }}
                                        </div><!-- End .product-price -->

                                        <div class="mt-n4 product-packaging">
                                            per {{product.packaging.name}}
                                        </div>
                                    </div><!-- End .product-body -->
                                </div><!-- End .product -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import CartUpdateButton from '@/Components/Controls/CartUpdateButton.vue'
import { onMounted, ref } from '@vue/runtime-core'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    components: {
        AppLayout,
        CartUpdateButton,
    },

    setup() {
        const pageProps = usePage().props.value
        const productsContainerRef = ref(null)
        onMounted(() => {
            $(productsContainerRef.value).owlCarousel({
                navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
            })
        })

        return {
            productsContainerRef,
            pageProps,
        }
    }
}
</script>
