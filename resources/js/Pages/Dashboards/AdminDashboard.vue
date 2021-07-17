<template>
    <app-layout>
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="d-flex justify-item-start allign-items-start my-3 alert-2 alert-2-success">
                            <alert-triangle />
                            <div class="flex-fill">
                                <h4>Admin dashboard</h4>
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

        components: {
            AppLayout,
        },
    }
</script>
