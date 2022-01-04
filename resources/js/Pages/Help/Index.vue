<template>
    <app-layout>
        <page-header :title="'How can we help you?'" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row">
                        <h3>List of help pages here</h3>
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

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
    },
    setup() {
        const breadcrumbLinks = [
            {
                text: 'Help center',
            },
        ];


        return {
            breadcrumbLinks,
        };
    },
};
</script>