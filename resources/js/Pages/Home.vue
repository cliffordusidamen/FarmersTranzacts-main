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
        }
    }
}
</script>
