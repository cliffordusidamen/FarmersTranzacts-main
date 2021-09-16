<template>
    <app-layout>
        <page-header :title="title || 'Page title'" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row">
                        <div class="col-12" v-html="content || ''"></div>
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
        const { title, content } = usePage().props.value.pageSetup
        const breadcrumbLinks = [
            {
                text: title,
            },
        ];


        return {
            breadcrumbLinks,
            title,
            content,
        };
    },
};
</script>