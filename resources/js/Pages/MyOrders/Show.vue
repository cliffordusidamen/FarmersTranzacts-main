<template>
    <app-layout>
        <page-header title="Orders Details" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <p class="text-right text-uppercase" v-if="order.cancellable">
                                <strong class="text-dark">Status: </strong>
                                {{ order.delivery_status.name }}
                            </p>

                            <div v-for="vendorOrder in order.vendor_orders" :key="vendorOrder.id">
                                <div class="card border p-3 rounded-sm shadow mb-3">
                                    <div class="card-header py-4 border-bottom">
                                        <div class="d-flex flex-column-reverse justify-content-md-between flex-md-row">
                                            <div>
                                                <strong class="mr-2 text-muted">SHOP NAME:</strong>
                                                {{ vendorOrder.vendor.shop_name }}
                                            </div>

                                            <div class="text-right">
                                                <span v-if="vendorOrder.cancellable">
                                                    <inertia-link
                                                        :href="route('my_orders.show_cancel_vendor_order', {vendor_order: vendorOrder.id})"
                                                        class="btn btn-primary btn-sm text-uppercase"
                                                    >
                                                        cancel order
                                                    </inertia-link>
                                                </span>
                                                <span v-else>{{ vendorOrder.delivery_status.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-sm mb-0 table-mobile">
                                        <thead>
                                            <tr>
                                                <th class=" text-uppercase">Product</th>
                                                <th class="text-right text-uppercase">Price(&#8358;)</th>
                                                <th class="text-right text-uppercase">Quantity</th>
                                                <th class="text-right text-uppercase">Total(&#8358;)</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="item in vendorOrder.items" :key="item.id">
                                                <td class="product-col">
                                                    <div class="product">
                                                        <figure class="product-media my-2">
                                                            <inertia-link
                                                                :href="route('shop.show_product', { slug: item.product.slug })"
                                                            >
                                                                <img
                                                                    :src="item.product.feature_image"
                                                                    alt="Product image"
                                                                    class="product-image"
                                                                />
                                                            </inertia-link>
                                                        </figure>

                                                        <h3 class="product-title">
                                                            <inertia-link
                                                                :href="route('shop.show_product', { slug: item.product.slug })"
                                                            >
                                                                {{ item.product.name }}
                                                            </inertia-link>
                                                        </h3><!-- End .product-title -->
                                                    </div><!-- End .product -->
                                                </td>
                                                <td class="price-col text-right">{{ Number(item.unit_amount).toLocaleString() }}</td>
                                                <td class="text-right quantity-col">
                                                    {{ Number(item.quantity).toLocaleString() }}
                                                </td>
                                                <td class="text-right total-col">
                                                    {{ Number(item.total_amount).toLocaleString() }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="text-right text-uppercase text-bold">Subtotal</td>
                                                <td class="text-right total-col">{{ Number(vendorOrder.items_amount).toLocaleString()}}</td>
                                            </tr><!-- End .summary-subtotal -->

                                            <tr>
                                                <td colspan="3" class="text-right text-uppercase text-bold">
                                                    Delivery ({{ Number(vendorOrder.delivery_days) }} Days)
                                                </td>
                                                <td class="text-right total-col">
                                                    {{ Number(vendorOrder.delivery_amount).toLocaleString()}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="text-right text-uppercase text-bold">Total</td>
                                                <td class="text-right total-col">{{ Number(vendorOrder.total_amount).toLocaleString()}}</td>
                                            </tr><!-- End .summary-total -->
                                        </tbody>
                                    </table>
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
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { usePage } from '@inertiajs/inertia-vue3';
import useDate from '@/composables/useDate'
import Pagination from '@/Components/Pagination.vue'

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
        Pagination,
    },
    setup() {

        const { order } = usePage().props.value
        const { formatDate } = useDate()
        const breadcrumbLinks = [
            {
                link: route('my_orders.index'),
                text: 'My Orders'
            },
            {
                text: order.reference_number,
            },
        ];

        return {
            breadcrumbLinks,
            order,
            formatDate,
        };
    },
};
</script>