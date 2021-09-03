<template>
    <app-layout :show-categories="false">
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />

        <div class="page-content">
            <div class="container">
                <div class="product-details-top">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="product-gallery product-gallery-vertical">
                                <div class="row">
                                    <figure class="product-main-image">
                                        <img id="product-zoom" src="/img/default_product.png" data-zoom-image="/img/default_product.png" alt="product image">

                                        <a href="#" id="btn-product-gallery" class="btn-product-gallery">
                                            <i class="icon-arrows"></i>
                                        </a>
                                    </figure><!-- End .product-main-image -->

                                    <div id="product-zoom-gallery" class="product-image-gallery">
                                        <a class="product-gallery-item active" href="#" data-image="/img/default_product.png" data-zoom-image="/img/default_product.png">
                                            <img src="/img/default_product.png" alt="product side">
                                        </a>

                                        <a class="product-gallery-item" href="#" data-image="/img/default_product.png" data-zoom-image="/img/default_product.png">
                                            <img src="/img/default_product.png" alt="product cross">
                                        </a>

                                        <a class="product-gallery-item" href="#" data-image="/img/default_product.png" data-zoom-image="/img/default_product.png">
                                            <img src="/img/default_product.png" alt="product with model">
                                        </a>

                                        <a class="product-gallery-item" href="#" data-image="/img/default_product.png" data-zoom-image="/img/default_product.png">
                                            <img src="/img/default_product.png" alt="product back">
                                        </a>
                                    </div><!-- End .product-image-gallery -->
                                </div><!-- End .row -->
                            </div><!-- [END] product-gallery -->
                        </div><!-- [END] col-md-6 -->
                        <div class="col-md-6">
                            <div class="product-details">
                                <h1 class="product-title">{{ product.name }}</h1>
                                <div class="product-price">
                                    &#8358;{{ Number(product.price).toLocaleString() }}
                                </div><!-- End .product-price -->

                                <div class="product-content">
                                    <p>{{ product.description }}</p>

                                </div>
                               <div class="product-content">
                                   <h5>Shipping &amp; Returns</h5>
                                   <p>
                                       Items will be shipped within xdays.
                                       Items can only be returned within 30 days of delivery.
                                   </p>
                                </div>

                                <div class="product-details-footer">
                                    <strong class="mr-2">SHOP:</strong>
                                    {{ product.vendor.shop_name }}
                                </div>
                            </div>
                        </div>
                    </div><!-- [END] row -->
                </div><!-- [END] product-details-top -->
            </div><!-- [END] container -->
        </div><!-- [END] page-content -->
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    components: {
        AppLayout,
        BreadcrumbDefault
    },
    setup() {
        const {product} = usePage().props.value
        const breadcrumbLinks = [
            {
                text: "Shop",
                link: route("shop.index"),
            },
            {
                text: product.name,
            },
        ];

        return {
            breadcrumbLinks,
            product,
        };
    },
};
</script>