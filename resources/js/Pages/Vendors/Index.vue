<template>
    <app-layout :show-categories="false">
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div v-if="vendors.total == 0" class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="border bg-white p-3 rounded-md lead">
                            No vendor found at the moment!
                        </div>
                    </div>
                </div>
                <div v-else class="row">
                    <div class="col-md-10">
                        <div class="card">
                            <table class="table table-bordered table-sm admin-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Products</th>
                                        <th>Address</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="vendor in vendors.data" :key="vendor.id">
                                        <td>{{ vendor.name }}</td>
                                        <td>
                                            {{ productCategoriesList(vendor.product_categories) }}
                                        </td>
                                        <td>
                                            {{ vendor.address.line_1}},
                                            <span v-if="vendor.address.line_2" class="d-block">
                                                {{ vendor.address.line_2 }},
                                            </span>
                                            <span v-if="vendor.address.city">
                                                {{ vendor.address.city }},
                                            </span>
                                            <span v-if="vendor.address.state">
                                                {{ vendor.address.state.name }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ `${vendor.users[0].first_name} ${vendor.users[0].last_name}` }}<br>
                                            {{ vendor.users[0].email }}
                                        </td>
                                        <td class="vendor-status" :data-vendor="vendor.id">{{ vendor.approval_status }}</td>
                                        <td class="text-right">
                                            <div class="d-flex justify-content-end">
                                                <vendor-action-button :vendor="vendor" @status-changed="refreshPage" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-show="vendors.last_page > 1" class="card-footer">
                                <pagination :paginator="vendors" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"
import Pagination from '@/Components/Pagination'
import VendorActionButton from '@/Components/VendorActionButton'
import Utility from "@/Mixins/utility.js"

export default {
    props: ['vendors'],

    components: {
        AppLayout,
        Pagination,
        VendorActionButton,
    },

    mixins: [Utility],

    methods: {
        refreshPage(vendor) {
            const $statusCell = document.querySelectorAll('.vendor-status[data-vendor="' + vendor.id + '"]')[0]
            $statusCell.innerHTML = vendor.approval_status;
            // window.location.reload();
        },
    }
}
</script>