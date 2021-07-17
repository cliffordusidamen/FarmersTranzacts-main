<template>
    <app-layout>
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div v-if="approvalPending" class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="d-flex justify-item-start allign-items-start my-3 alert-2 alert-2-warning">
                            <div class="flex-fill">
                                <h4>Vendor registration approval pending</h4>
                                <p>
                                    Your vendor registration is still awaiting approval. This may take between few days to a week.
                                    We may reach out to you within this period to verify some of the information provided in your registration.
                                </p>
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
