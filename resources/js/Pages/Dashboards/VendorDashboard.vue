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
                        <h3>Dashboard details</h3>
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
