<template>
    <app-layout>
        <page-header title="My Orders" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div v-if="!orders?.data?.length" class="error-content text-center bg-white">
                        <h2 class="error-title">No item ordered yet!</h2>
                        <p class="h4 text-muted">We have a lot of great items &amp; offers for you!</p>
                        <inertia-link :href="route('shop.index')" class="btn btn-primary btn-minwidth-lg">
                            <span>START SHOPPING NOW</span>
                            <i class="icon-long-arrow-right"></i>
                        </inertia-link>
                    </div>

                    <div v-else>
                        <div class="card shadow">
                            <div
                                v-if="!orders?.data?.length"
                                class="card-body lead"
                            >
                                No orders made
                            </div>
                            <table class="table table-sm mb-0 table-bordered">
                                <thead>
                                    <tr class="d-none d-sm-table-row">
                                        <th class="d-none text-uppercase d-sm-table-cell">Date</th>
                                        <th class="text-uppercase">Order</th>
                                        <th class="d-none text-uppercase d-sm-table-cell">Amount(&#8358;)</th>
                                        <th class="d-none text-uppercase d-sm-table-cell">Items</th>
                                        <th class="d-none text-uppercase d-sm-table-cell">Status</th>
                                        <th class="d-none text-uppercase d-sm-table-cell text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="order in orders.data"
                                        :key="order.id"
                                    >
                                        <td class="d-none d-sm-table-cell">{{ formatDate(order.created_at) }}</td>
                                        <td>
                                            <inertia-link
                                                :href="route('my_orders.show', order)"
                                            >
                                                <h4>{{ order.reference_number }}</h4>
                                            </inertia-link>

                                            <div class=" mx-3 mb-3 d-sm-none lead">
                                                <p>
                                                    {{ formatDate(order.created_at) }}<br />
                                                    &#8358; {{ Number(order.amount).toLocaleString() }}
                                                    ({{ order.items_count }} items)
                                                </p>

                                                <p>
                                                    <strong class="text-uppercase">Status: </strong>
                                                    {{ order.delivery_status.name }}
                                                </p>

                                                <inertia-link
                                                    :href="route('my_orders.show', order)"
                                                    class="btn btn-primary btn-sm"
                                                >
                                                    View
                                                </inertia-link>
                                            </div>
                                        </td>

                                        <td class="d-none d-sm-table-cell">
                                            {{ Number(order.amount).toLocaleString() }}
                                        </td>

                                        <td class="d-none d-sm-table-cell">
                                            {{ order.items_count }}
                                        </td>

                                        <td class="d-none text-capitalize d-sm-table-cell">
                                            {{ order.delivery_status.name }}
                                        </td>

                                        <td class="d-none d-sm-table-cell text-right">
                                            <inertia-link
                                                :href="route('my_orders.show', order)"
                                                class="btn btn-link"
                                            >
                                                View
                                            </inertia-link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="my-4">
                            <pagination :paginator="orders" />
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
        const breadcrumbLinks = [
            {
                text: "My Orders",
            },
        ];

        const { orders } = usePage().props.value
        const { formatDate } = useDate()

        return {
            breadcrumbLinks,
            orders,
            formatDate,
        };
    },
};
</script>