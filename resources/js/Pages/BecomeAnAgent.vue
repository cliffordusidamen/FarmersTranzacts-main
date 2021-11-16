<template>
    <app-layout>
        <page-header :title="'Become an agent'" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                Become an agent
                            </h4>
                            <inertia-link
                                :href="route('registration.agent')"
                                class="btn btn-primary">
                                REGISTER
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    components: {
        AppLayout,
        PageHeader,
    },
    setup() {
    },
};
</script>