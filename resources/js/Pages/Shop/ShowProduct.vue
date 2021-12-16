<template>
    <app-layout :show-categories="true">
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />

        <div class="page-content">
            <div class="container">
                <div class="product-details-top">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="product-gallery product-gallery-vertical">
                                <div class="row">
                                    <figure class="product-main-image">
                                        <img id="product-zoom" :src="product.feature_image" :data-zoom-image="product.feature_image" alt="product image">

                                        <a href="#" id="btn-product-gallery" class="btn-product-gallery">
                                            <i class="icon-arrows"></i>
                                        </a>
                                    </figure><!-- End .product-main-image -->

                                    <div id="product-zoom-gallery" class="product-image-gallery overflow-hidden">
                                        <a
                                            class="product-gallery-item active"
                                            href="#"
                                            :data-image="product.feature_image"
                                        >
                                            <img :src="product.feature_image" :alt="`Main Picture`">
                                        </a>
                                        <a
                                            v-for="image in getOtherImages(product.images)"
                                            :key="image.id"
                                            class="product-gallery-item"
                                            href="#"
                                            :data-image="getImagePath(image.path)"
                                        >
                                            <img :src="getImagePath(image.path)" :alt="`Picture`">
                                        </a>
                                    </div><!-- End .product-image-gallery -->
                                </div><!-- End .row -->
                            </div><!-- [END] product-gallery -->
                        </div><!-- [END] col-md-6 -->
                        <div class="col-md-6">
                            <div class="product-details mx-3">
                                <h1 class="product-title">{{ product.name }}</h1>
                                <div class="product-price text-success">
                                    &#8358;{{ Number(product.price).toLocaleString() }} per {{product.packaging.name}}
                                </div><!-- End .product-price -->

                                <div class="product-content" v-html="product.description"></div>
                                <div class="product-content">
                                    <h6>Delivery</h6>
                                    <div class="border-top">
                                        <product-delivery-cost-item :item="item" :product-package="product.packaging.name" v-for="item in product.delivery_costs" :key="item.id" />
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-6 col-md-5">
                                            <p>
                                                <cart-update-button :product="product" />
                                            </p>

                                       </div>
                                    </div>
                                </div>

                                <div class="product-details-footer">
                                    <strong class="mr-2 lead">SHOP:</strong>
                                    <inertia-link
                                        :href="route('vendor_page', { slug: product.vendor.slug })"
                                    >
                                        {{ product.vendor.shop_name }}
                                    </inertia-link>
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
import CartUpdateButton from "@/Components/Controls/CartUpdateButton.vue";
import ProductDeliveryCostItem from "@/Components/ProductDeliveryCostItem.vue";
import { usePage } from '@inertiajs/inertia-vue3';
import { onMounted, onUnmounted } from '@vue/runtime-core';

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        CartUpdateButton,
        ProductDeliveryCostItem,
    },
    setup() {
        const {product} = usePage().props.value
        console.log('product', product)
        const breadcrumbLinks = [
            {
                text: "Shop",
                link: route("shop.index"),
            },
            {
                text: product.name,
            },
        ];

        onMounted(() => {
            $('#product-zoom').elevateZoom({
                gallery:'product-zoom-gallery',
                galleryActiveClass: 'active',
                zoomType: "outer",
                cursor: "crosshair",
                zoomWindowFadeIn: 800,
                zoomWindowFadeOut: 800,
                responsive: true
            });

            // On click change thumbs active item
            $('.product-gallery-item').on('click', function (e) {
                $('#product-zoom-gallery').find('a').removeClass('active');
                $(this).addClass('active');

                e.preventDefault();
            });

            var ez = $('#product-zoom').data('elevateZoom');

            // Open popup - product images
            $('#btn-product-gallery').on('click', function (e) {
                if ( $.fn.magnificPopup ) {
                    $.magnificPopup.open({
                        items: ez.getGalleryList(),
                        type: 'image',
                        gallery:{
                            enabled:true
                        },
                        fixedContentPos: false,
                        removalDelay: 600,
                        closeBtnInside: false
                    }, 0);

                    e.preventDefault();
                }
            });
        })


        onUnmounted(() => {
            const $zoomContainers = $('.zoomContainer');

            if ($zoomContainers.length) {
                $zoomContainers.remove();
            }
        })
        const getImagePath = (path) => {
            return path.replace(/^(public\/)/, '/storage/')
        }

        const getOtherImages = (images) => {
            return images.filter(image => !image.is_featured)
        }

        return {
            breadcrumbLinks,
            product,
            getImagePath,
            getOtherImages,
        };
    },
};
</script>