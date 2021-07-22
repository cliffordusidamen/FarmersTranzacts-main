<template>
    <app-layout>
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div v-if="approvalPending || approvalDisapproved" class="row justify-content-center">
                    <div class="col-md-8">
                        <div v-if="approvalPending" class="d-flex justify-item-start allign-items-start my-3 alert-2 alert-2-warning">
                            <div class="flex-fill">
                                <h4>Vendor registration approval pending</h4>
                                <p>
                                    Your vendor registration is awaiting approval. This may take between few days to a week.
                                    We may reach out to you within this period to verify some of the information provided in your registration.
                                </p>
                            </div>
                        </div>
                        <div v-else-if="approvalDisapproved" class="d-flex justify-item-start allign-items-start my-3 alert-2 alert-2-warning">
                            <div class="flex-fill">
                                <h4>Vendor registration disapproved</h4>
                                <p>
                                    Your vendor registration was not approved at this time.
                                </p>
                                <p v-if="user_vendor.reject_reason">
                                    <strong>Reason for disapproval:</strong>
                                    {{ user_vendor.reject_reason }}
                                </p>
                                <p class="my-3 text-right">
                                    <inertia-link class="btn btn-primary" :href="route('profile.edit_vendor_disapproval')">Edit Registration</inertia-link>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4 col-6">
                                <div class="dashboard-stat">
                                    <div class="number">13</div>
                                    <div class="title">Products</div>
                                </div>
                            </div>

                            <div class="col-md-4 col-6">
                                <div class="dashboard-stat">
                                    <div class="number">45</div>
                                    <div class="title">Orders</div>
                                </div>
                            </div>

                            <div class="col-md-4 col-">
                                <div class="dashboard-stat">
                                    <div class="number">7</div>
                                    <div class="title">Agents</div>
                                </div>
                            </div>
                        </div>

                        <div class="row my-4">
                            <div class="col-md-6">
                                <strong>Recent orders</strong>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="#" class="btn btn-primary btn-sm">
                                    <span class="icon-plus"></span>
                                    Add product
                                </a>
                            </div>
                        </div>

                        <div class="card">
                            <table class="table table-bordered table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold text-uppercase">Amount</th>
                                        <th class="font-weight-bold text-uppercase">Shopper</th>
                                        <th class="font-weight-bold text-uppercase">Items</th>
                                        <th class="font-weight-bold text-uppercase">Date</th>
                                        <th class="font-weight-bold text-uppercase">Status</th>
                                        <th class="text-right font-weight-bold text-uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th colspan="6" class="text-center">
                                            No product available!
                                        </th>
                                    </tr>
                                    <tr class="d-none">
                                        <td>Amount</td>
                                        <td>Shopper</td>
                                        <td>Items</td>
                                        <td>Date</td>
                                        <td>Status</td>
                                        <td class="text-right">Actions</td>
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
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        props: [
            'user_vendor',
            'approvalPending',
            'approvalDisapproved',
        ],

        components: {
            AppLayout,
        },
    }
</script>
