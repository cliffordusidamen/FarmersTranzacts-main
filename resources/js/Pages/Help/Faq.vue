<template>
    <app-layout>
        <page-header title="Frequently Asked Questions" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <faq-accordion :faqs="faqs" />
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
import FaqAccordion from '@/Components/FaqAccordion.vue'
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
        FaqAccordion,
    },
    setup() {

        const { faqs } = usePage().props.value
        const breadcrumbLinks = [
            {
                link: route('help.index'),
                text: 'Help center',
            },
            {
                text: 'FAQ',
            },
        ];


        return {
            breadcrumbLinks,
            faqs,
        };
    },
};
</script>