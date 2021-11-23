<template>
    <app-layout>
        <vendor-page-header :vendor="vendor" />
        <vendor-page-nav />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            
                            <div v-if="!products?.total" class="border h4 p-3 rounded-md lead">
                                {{ 'No product available at the moment!' }}
                            </div>

                            <div v-else class="tab-content">
                                <div
                                    id="vendor-page-products"
                                    class="tab-pane fade show active"
                                >
                                    <h3>Products</h3>
                                    <div class="products mb-3">
                                        <div class="row justify-content-start">
                                            <div v-for="product in products.data" :key="product.id" class="col-11 col-sm-6 col-md-3">
                                                <product-box :product="product" />
                                            </div>
                                        </div>
                                    </div>

                                    <pagination v-if="products?.links?.length" :paginator="products" />
                                </div>

                                <div
                                    id="vendor-page-about"
                                    class="tab-pane fade"
                                >
                                    <h3>About</h3>
                                    {{ vendor.about }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import VendorPageNav from "@/Components/Utils/VendorPageNav.vue";
import VendorPageHeader from "@/Components/Utils/VendorPageHeader.vue";
import { usePage } from '@inertiajs/inertia-vue3';
import Pagination from '@/Components/Pagination.vue'
import ProductBox from '@/Components/ProductBox.vue'

export default {
    components: {
        AppLayout,
        VendorPageNav,
        VendorPageHeader,
        Pagination,
        ProductBox,
    },
    setup() {
        const { vendor, vendorProducts: products } = usePage().props.value

        return {
            vendor,
            products,
        };
    },
};
</script>