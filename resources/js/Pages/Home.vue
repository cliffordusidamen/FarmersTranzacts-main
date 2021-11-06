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

                            <div
                                ref="productsContainerRef"
                                class="owl-carousel owl-simple owl-nav-top carousel-equal-height carousel-with-shadow mt-4"
                                data-toggle="owl"
                            >
                                <product-box
                                    v-for="product in pageProps.products"
                                    :key="product.id"
                                    :product="product"
                                    class="mx-4"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="categories-page">
                <div class="container" v-if="categoriesWithDisplayPicture.length > 3">
                    <h2 class="title title-border my-4">Categories</h2>

                    <div class="entry-container max-col-6">
                        <div
                            v-for="category in categoriesWithDisplayPicture"
                            :key="category.id"
                            class="entry-item col-sm-6 col-md-4"
                        >
                            <div class="banner banner-cat banner-badge">
                                <inertia-link :href="route('shop.categorized', {slug: category.slug})">
                                    <img
                                        :src="category.display_picture"
                                        alt="{{ category.name }}"
                                        class="img-fluid"
                                    />
                                </inertia-link>

                                <inertia-link
                                    class="banner-link"
                                    :href="route('shop.categorized', {slug: category.slug})"
                                >
                                    <h3 class="banner-title">
                                        {{ category.name }}
                                    </h3>

                                    <h4 class="banner-subtitle">
                                        {{ category.products_count }} products
                                    </h4>
                                    <span class="banner-link-text">Shop Now</span>
                                </inertia-link>
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
import ProductBox from '@/Components/ProductBox.vue'
import { onMounted, ref } from '@vue/runtime-core'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    components: {
        AppLayout,
        CartUpdateButton,
        ProductBox,
    },

    setup() {
        const pageProps = usePage().props.value
        const productsContainerRef = ref(null)
        const categoriesWithDisplayPicture = pageProps?.categoriesWithDisplayPicture?.data || []

        onMounted(() => {
            $(productsContainerRef.value).owlCarousel({
                navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
                nav: true, 
                dots: false,
                margin: 20,
                loop: false,
                responsive: {
                    "0": {
                        "items":2
                    },
                    "360": {
                        "items":2
                    },
                    "768": {
                        "items":3
                    },
                    "1200": {
                        "items":4
                    }
                }
            })
        })

        return {
            productsContainerRef,
            pageProps,
            categoriesWithDisplayPicture,
        }
    }
}
</script>
