<template>
    <app-layout :show-categories="false">
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-5">
                                <inertia-link :href="route('vendors.index')">
                                    &laquo;
                                    Back to list
                                </inertia-link>
                            </div>
                            <div class="col-md-7">
                                <p class="my-3 text-right">
                                    <vendor-action-button :vendor="vendor" @status-changed="statusChanged" />
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th width="30%">Organization Name</th>
                                        <td>{{ vendor.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Vendor Phone</th>
                                        <td>{{ vendor.phone }}</td>
                                    </tr>
                                    <tr v-if="vendor.email">
                                        <th>Vendor email</th>
                                        <td>{{ vendor.email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
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
                                    </tr>
                                    <tr>
                                        <th>Owner Name</th>
                                        <td>{{ `${vendor.users[0].first_name} ${vendor.users[0].last_name}` }}</td>
                                    </tr>
                                    <tr v-if="vendor.users[0].phone">
                                        <th>Owner Phone</th>
                                        <td>{{ vendor.users[0].phone }}</td>
                                    </tr>
                                    <tr v-if="vendor.users[0].email">
                                        <th>Owner Email</th>
                                        <td>{{ vendor.users[0].email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Product {{ Object.keys(vendorProductCategories).length == 1 ? 'Category' : 'Categories' }}</th>
                                        <td>
                                            {{ productCategoriesList(vendorProductCategories) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ refVendor.approval_status }}</td>
                                    </tr>
                                    <tr v-show="refVendor.reject_reason">
                                        <th>Disapproval reason</th>
                                        <td>{{ refVendor.reject_reason }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"
import VendorActionButton from '@/Components/VendorActionButton'

export default {
    props: ['vendor', 'vendorProductCategories'],
    components: {
        AppLayout,
        VendorActionButton,
    },

    data() {
        return {
            updatedVendor: null,
        };
    },

    computed: {
        refVendor() {
            return this.updatedVendor || this.vendor;
        },
    },

    methods: {
        refreshPage() {
            window.location.reload();
        },

        statusChanged(vendor) {
            this.updatedVendor = vendor;
        },
    }
}
</script>